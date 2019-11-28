<?php
abstract class animal
{
    protected $weight; // типизация для 7.4 protected ?float $weight;
    protected $age; // типизация для 7.4 protected ?int $age;
    protected $name; // типизация для 7.4 protected ?string $name;
    protected $ownerName;  // типизация для 7.4 protected ?string $ownerName;

    function __construct(int $age, float $weight, string $name, string $ownerName)
    {
        $this->weight = $weight;
        $this->age = $age;
        $this->name = $name;
        $this->ownerName = $ownerName;
    }

    function getWeight():float {
        return $this->weight;
    }

    function getAge():int {
        return $this->age;
    }

    function getName():string {
        return $this->name;
    }

    function getOwnerName():string {
        return $this->ownerName;
    }

    abstract function getInstance();
}
class dog extends animal {
    function getInstance() {
        return $this;
    }
}
class bird extends animal {

    private $wingspan;

    function __construct(int $age, float $weight, string $name, string $ownerName, float $wingspan)
    {
        parent::__construct($age, $weight, $name, $ownerName);
        $this->wingspan = $wingspan;
    }

    function getWingspan():float {
        return $this->wingspan;
    }
    function getInstance() {
        return $this;
    }
}

class Cat extends Animal  {

    function getInstance()
    {
        return $this;
    }

    function getInformation(): string
    {
        return "Кличка:{$this->name}, возраст: {$this->age}, вес: {$this->weight}, хозяин: {$this->ownerName}";
    }
}


$dog = new Dog(21,1000.20, 'Мурзик', 'Игорь Иванович');
echo $dog->getName();
$dogInstance = $dog->getInstance();
var_dump($dogInstance);

$bird  = new Bird(21,1000.20, 'Попугайка', 'Ира Захарова', 20.02);
echo $bird->getWingspan();

$cat = new Cat(21,1000.20, 'Жулик', 'Игорь Иванович');
echo $cat->getInformation();