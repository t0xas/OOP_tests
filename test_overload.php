<?php
class test{

    private $v1;
    private $v2;

    public function __construct(int $v1, int $v2)
    {
        $this->v1 = $v1;
        $this->plus();
    }

    public function printResult(){
        echo $this->v1;
    }

    private function plus(){
        $this->v1++;
    }

    public function __get(string $property) {
        if($property == 'v1') {
            throw new Exception('Access denied to property'.$property);
        }

        if($property == 'v2') {
            return $this->v2 = $this->v2+1;
        }
    }

    public function __isset($name)
    {
        if(!isset($this->$name)) {
            echo "variable $name not found";
        }

        return isset($this->$name);
    }


    public function __set(string $property, int $value) {
        $value++;
        $this->$property = $value;
    }
}


    $test = new Test(2, 1);
    $test->printResult();
    echo "\n";
    $test->newvalue = 1;
    echo $test->newvalue;
    echo "\n";
    if(!isset($test->v5)) {
        echo "\n";
        echo "Reality not found";
    }
    if(isset($test->newvalue)) {
        echo "\n";
        echo "New value found";
    }

    echo "\n";
    echo $test->v2;
    echo "\n";
    echo $test->v2;
    echo "\n";

    try {
        echo $test->v1;
    }
    catch (Exception $e) {
        echo $e->getMessage(), "\n";
    }

