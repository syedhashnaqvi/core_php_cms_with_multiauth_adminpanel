<?php
//Constants

// Base Url
const BASE_URL = "/core_php_cms";
const MEDIA_PATH = "/Media/";


//Database connection
$database =[
    'host'=>'localhost:3306',
    'db'=>'phpcms',
    'user'=>'root',
    'pass'=>'',
    'charset' => 'utf8',
];


//allowed file types
$allowedFileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');

//allowed media file types
$allowedMediaFileExtensions = array('jpg', 'jpeg', 'gif', 'png', 'webm', 'mp4', 'avi');