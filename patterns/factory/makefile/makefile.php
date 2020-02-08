<?php
namespace makefile;
abstract class makefile {
    protected $_text;
    abstract public function makeFile();
    public function setContent($text) {
        $this->_text = $text;
    }
}