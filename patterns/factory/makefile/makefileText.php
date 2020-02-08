<?php


namespace makefile;


class makefileText extends makefile
{

    public function makeFile()
    {
        echo $this->_text. " TEXT";
    }
}