<?php

namespace App;
use root\DB;
use root\Sessions;

class Auth
{

    public static function attemptLogin($auth_email,$auth_password,$table)
    {
        $user = DB::getInstance()->query('SELECT * FROM '.$table.' WHERE `email`="'.$auth_email.'" AND `password`="'.$auth_password.'" LIMIT 1')->fetchObject();
        if((array)$user)
        {
            unset($user->password);
            Sessions::session()->create_session($user->is_admin?'admin':'user',true,$user);
            return true;
        }else{
            return false;
        }
    }

    public static function logout()
    {
        Sessions::session()->remove_session('admin');
    }


    public static function getAdmin($colmnName=''){
        if($colmnName == '')
        {
            return Sessions::session()->get_session_data('admin');
        }
        $data = Sessions::session()->get_session_data('admin');

        $data = (array)$data;
        if($data){
            __($data[$colmnName]);
        }
        return false;
    }

    public static function getUser($colmnName=''){
        if($colmnName == '')
        {
            return Sessions::session()->get_session_data('user');
        }
        $data = Sessions::session()->get_session_data('user');
        $data = (array)$data;
        if($data){
            __($data[$colmnName]);
        }
        return false;
    }
}