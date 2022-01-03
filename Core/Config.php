<?php
namespace Core;
/*Change config values in App/config.php file
Config loader Class*/

class Config {
    public static function get($keys)
    {
        $configs = require('App'.DS.'config'.DS.'config.php');
        $configs = json_decode(json_encode($configs));
        $keys = explode(".",$keys);
        foreach($keys as $key){
            if(!isset($configs->$key)){
                $configs = NULL;
                break;
            }
            $configs = $configs->$key;
        }
        return $configs;
    }
}