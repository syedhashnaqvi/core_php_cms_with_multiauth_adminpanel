<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
const DS = DIRECTORY_SEPARATOR;

require('Core'.DS.'Config.php');
require('Core'.DS.'Sessions.php');
require('Core'.DS.'Validator.php');
require('Helpers'.DS.'helper.php');
require('Core'.DS.'Template.php');
require('Core'.DS.'Request.php');
require('Core'.DS.'Router.php');
require('Core'.DS.'DB.php');
require('App'.DS.'Auth.php');
require('Core'.DS.'Hash.php');