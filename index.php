<?php

interface Catinstruction {
    function getInformation():string;
}

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

class Cat extends Animal implements Catinstruction {

    function getInstance()
    {
        // TODO: Implement getInstance() method.
        return $this;
    }

    function getInformation(): string
    {
        // TODO: Implement getInformation() method.
        echo "Кличка:{$this->name}, возраст: {$this->age}, вес: {$this->weight}, хозяин: {$this->ownerName}";
    }
}

class NoAnimal implements Catinstruction {

    private $param = array();

    public function __construct(array $param)
    {
        $this->param = $param;
    }

    function getInstance()
    {
        // TODO: Implement getInstance() method.
        return $this;
    }

    function getInformation(): string
    {
        // TODO: Implement getInformation() method.
        var_dump($this->param);
    }
}

class test {
    function testAnimal(Animal $instance) {
        var_dump($instance);
    }
    function testBird(Bird $instance){
        var_dump($instance);
    }
    function testCat(Cat $instance){
        var_dump($instance);
    }
    function testCatInstruction(Catinstruction $instance){
        var_dump($instance);
    }
}

$dog = new Dog(21,1000.20, 'Мурзик', 'Игорь Иванович');
echo $dog->getName();
$birdInstance = $dog->getInstance();

$bird  = new Bird(21,1000.20, 'Попугайка', 'Ира Захарова', 20.02);
echo $bird->getWingspan();
$birdInstance = $bird->getInstance();

$cat = new Cat(21,1000.20, 'Жулик', 'Игорь Иванович');

$test = new Test();
$test->testAnimal($bird);
$test->testBird($bird);
$test->testCat($cat);
$test->testCatInstruction($cat);

$noanimal = new NoAnimal([1,2,3,4]);
$test->testCatInstruction($noanimal->getInstance());

echo "Master changes";