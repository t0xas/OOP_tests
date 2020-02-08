<?php
namespace makefile;

class factoryText extends factory
{

    public static function create(): makefile
    {
        return new makefileText();
    }
}