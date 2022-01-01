<?php
require 'vendor/autoload.php';
include_once 'Core'.DIRECTORY_SEPARATOR.'ServiceLoader.php';
$router = new Router(new Request);