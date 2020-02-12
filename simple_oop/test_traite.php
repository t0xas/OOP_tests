<?php
trait testTrait1 {
    abstract function abstr():string;

    function publicFunction():int {
        return $this->abstr();
    }
}

trait testTrait2 {
    abstract function abstr():string;

    function publicFunction():int {
        return strrev($this->abstr());
    }

}

class Base {

    use testTrait2;

    function baseMethod():string {
        return "baseMethod";
    }

    function abstr()
    {
       $string = "Какая-то строчка";
       echo $string;
    }
}
class Auto extends Base {
    use testTrait1, testTrait2 {
        testTrait1::publicFunction insteadof  testTrait2;
        testTrait2::publicFunction as pb;
    }

    function abstr(): string
    {
        parent::abstr();
        return 123;
    }
}

$auto = new Auto();
echo $auto->publicFunction();
echo "\n";
echo $auto->pb();
echo "\n";
echo $auto->baseMethod();