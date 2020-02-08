<?php
spl_autoload_register(function ($class_name) {
    include strtolower($class_name) . '.php';
});

use makefile\factoryHtml;
use makefile\factoryJpg;
use makefile\factoryText;

$file = factoryJpg::create();
$file->setContent('Some text');
$file->makeFile();
echo "\n--------\n";
$file = factoryText::create();
$file->setContent('Some text');
$file->makeFile();
echo "\n--------\n";
$file = factoryHtml::create();
$file->setContent('Some text');
$file->makeFile();