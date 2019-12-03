<?php

class FileException extends Exception {}
class XmlException extends Exception {}

class Conf
{
    private $xml;

    function __construct(string $file) {
        if(!file_exists($file)) {
             throw new FileException("Файл $file не найден");
        }
        $this->xml = simplexml_load_file($file, null, LIBXML_NOERROR);

        $matches = $this->xml->xpath("server");
        if(!count($matches)) {
            throw new XmlException("Не найден элемент server");
        }
        $matches = $this->xml->xpath("database");
        if(!count($matches)) {
            throw new XmlException("Не найден элемент database");
        }
        $matches = $this->xml->xpath("user");
        if(!count($matches)) {
            throw new XmlException("Не найден элемент user");
        }
        $matches = $this->xml->xpath("password");
        if(!count($matches)) {
            throw new XmlException("Не найден элемент password");
        }
    }
    function getConfig(){
        return $this->xml;
    }
}

try {
    /*
        $conf = new Conf('cfg1.xml');
        FileException: Файл cfg1.xml не найден in test_exception.php:12
        Stack trace:
            #0 test_exception.php(32): Conf->__construct('cfg1.xml')
            #1 {main}
     * */

    /*
        $conf = new Conf('cfg_error.xml');
        XmlException: Не найден элемент server in test_exception.php:22
        Stack trace:
            #0 test_exception.php(40): Conf->__construct('cfg_error.xml')
            #1 {main}
        Process finished with exit code 0
     * */
    $conf = new Conf('cfg.xml');
}
catch (FileException $e) {
    die($e->__toString());
}
catch (XmlException $e) {
    die($e->__toString());
}

$conf = $conf->getConfig();
var_dump($conf);
/*
object(SimpleXMLElement)#2 (4) {
  ["server"]=>
  string(9) "localhost"
  ["database"]=>
  string(8) "database"
  ["user"]=>
  string(4) "root"
  ["password"]=>
  string(4) "root"
}

 */