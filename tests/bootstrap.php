<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . "/controllers.php";

// This should work for HHVM on travis
if (!class_exists('PHPUnit\\Framework\\TestCase')) {
    class_alias('PHPUnit_Framework_TestCase', 'PHPUnit\\Framework\\TestCase');
}
