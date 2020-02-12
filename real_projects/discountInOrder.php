<?php
/*
 * Скрипт реализован только для скидок в процентном соотношение.
 * Другой вариант не расматривался на текущий момент.
 * PHP > 7.1
 * Механизм работы:
 * 1. Получаем значение скидки от веса заказа
 * 2. Получаем скидку товара
 * 3. Сопостовляем скидку, если скидка на товар больше, то скидку от веса заказа не накладываем
 * 4. Если скидка от веса товара, больше скидки товара, то накладываем скидку от веса товара
 * 5. Суммируем все полученные скидки
 * 6. Формируем общую скидку
 * 7. Сохраняем значение общей скидки от веса, вместо текущей.
 */

class discountInOrder
{
    private $_shopId = 3;
    /*
     * Список ID скидок. которые накладываются на весь заказ и забирается от итоговой суммы.
     */
    private $_idDiscountByAll = 9;
    /*
     * скидка от веса товара, бывает только одна.
     */
    private $_discountWeight = 0;
    /*
     * название скидки которую будем имзенять
     */
    private $_nameDiscountWeight;
    /*
     * новое значение для скидки от веса товара
     */
    private $_amount;
    /*
     * Объекты заказа
     */
    private $_aItem = array();
    /*
     * общий вес заказа
     */
    private $_orderWeight;
    /*
     * объекты товаров в заказе
     */
    private $_aShopItem = array();

    function init($orderId):void
    {
        $this->_aItem = Core_Entity::factory('Shop_Order_item')->getAllByShop_order_id($orderId);
        $this->_orderWeight = $this->getOrderWeight();
        $this->_discountWeight = $this->getWeightDiscount();
        $this->shopItemPriceCorrect();
        $this->setNewDiscounts();
    }
    private function setNewDiscounts(): void
    {
        $oPurchaseDiscount = Core_Entity::factory('Shop_Purchase_Discount')->getById($this->_idDiscountByAll);
        //сумма для скидки от всей суммы
        $total = 0;
        foreach ($this->_aItem as $oItem) {
            //Записываем значение новой скидки от объема
            if ($oItem->name == $this->_nameDiscountWeight) {
                $oItem->price = $this->_amount;
                $total += $oItem->price;
                $oItem->save();
            }
            elseif($oItem->shop_item_id != 0) {
                $oShopItem = $this->_aShopItem[$oItem->shop_item_id];
                $discountShopItem = $this->getShopItemDiscount($oShopItem);
                if ($discountShopItem > $this->_discountWeight) {
                    $oItem->price = $oShopItem->price * (1 - $discountShopItem / 100);
                }
                else {
                    $oItem->price = $oShopItem->price;
                }
                $oItem->save();
                $total +=  $oItem->price * $oItem->quantity;
            }
        }
        // скидка от всей суммы товара, без доставки
        if($oPurchaseDiscount->type == 0) {
            foreach ($this->_aItem as $oItem) {
                if($oPurchaseDiscount->name == $oItem->name) {
                    $oItem->price = -$total*($oPurchaseDiscount->value/100);
                    $oItem->save();
                }
            }
        }
    }
    /*
     * Получает наибольшую скидку для товара
     */
    private function getShopItemDiscount($oShopItem){
        $aPrice = $oShopItem->getPrices();
        $discountShopItem = 0;
        $timestamp = time();
        foreach ($aPrice['discounts'] as $discount) {
            if (
                $discount->active == 1 &&
                $discount->type == 0 &&
                strtotime($discount->start_datetime) < $timestamp &&
                strtotime($discount->end_datetime) > $timestamp &&
                $discountShopItem < $discount->value
            ) {
                $discountShopItem = (float)$discount->value;
            }
        }
        return $discountShopItem;
    }
    /*
     * Корректирует цену на товар
     */
    private function shopItemPriceCorrect(): void
    {
        foreach ($this->_aItem as $oItem) {
            if ($oItem->shop_item_id == 0)
                continue;
            $oShopItem = $this->_aShopItem[$oItem->shop_item_id];
            //самая большай действующая скидка на товар
            $discountShopItem = $this->getShopItemDiscount($oShopItem);
            if ($discountShopItem > $this->_discountWeight) {
                //Скидка от товара
                $oItem->price = $oShopItem->price * (1 - $discountShopItem / 100);
                //$this->_amount -= $oShopItem->price * ($discountShopItem / 100) * $oItem->quantity;;
            } else {
                //считаем общую скидку для заказа
                $this->_amount -= $oShopItem->price * ($this->_discountWeight / 100) * $oItem->quantity;
            }
        }
    }
    /*
     * Получает скидку на вес заказа
     * реализовано для типа 0 - проценты
     * берется наибольшая скидка из всех предложенных
     */
    private function getWeightDiscount(): float
    {
        $oPurchaseDiscount = Core_Entity::factory('Shop_Purchase_Discount');
        $oPurchaseDiscount->queryBuilder()
            ->where('type', '=', 0)
            ->where('shop_id', '=', $this->_shopId)
            ->where('active', '=', 1) ;
        $aPurchaseDiscount = $oPurchaseDiscount->findAll();
        $discount = 0.00;
        foreach ($aPurchaseDiscount as $item) {
            if ($item->id == $this->_idDiscountByAll)
                continue;
            if ($discount < $item->value && $this->_orderWeight > $item->min_count && $this->_orderWeight < $item->max_count) {
                $discount = $item->value;
                $this->_nameDiscountWeight = $item->name;
            }
        }
        return $discount;
    }
    /*
     * Получает вес заказа
     */
    private function getOrderWeight(): float
    {
        $weigt = 0.00;
        foreach ($this->_aItem as $oItem) {
            if ($oItem->shop_item_id == 0)
                continue;
            $oShopItem = Core_Entity::factory('Shop_item')->getById($oItem->shop_item_id);
            $this->_aShopItem[$oShopItem->id] = $oShopItem;
            $weigt += (float)$oShopItem->weight * $oItem->quantity;
        }
        return $weigt;
    }
}