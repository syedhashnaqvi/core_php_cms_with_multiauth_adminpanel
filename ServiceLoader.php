<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
const DS = DIRECTORY_SEPARATOR;

require('Sessions.php');

require('App'.DS.'config.php');

require('Helpers'.DS.'helper.php');

require('Template.php');

require('Request.php');

require('Router.php');

require('DB.php');

require('App'.DS.'Auth.php');