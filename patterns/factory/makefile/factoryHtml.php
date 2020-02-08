<?php
namespace makefile;

class factoryHtml extends factory
{

    public static function create(): makefile
    {
        return new makefileHtml();
    }
}