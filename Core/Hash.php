<?php
namespace Core;

use Core\Config;
use mysqli;

class Hash{
    private static $options = [
        'cost' => 11
    ];

    public Static function makeHash($string)
    {
        return password_hash($string, PASSWORD_BCRYPT,self::$options);
    }

    public function verifyHash($string,$hash)
    {
        return password_verify($string, $hash);
    }

}