<?php
namespace makefile;

class factoryJpg extends factory
{

    public static function create(): makefile
    {
        return new makeFileJpg();
    }
}