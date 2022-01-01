<?php

namespace App;
use root\DB;
use root\Sessions;

class Auth
{

    public static function attemptLogin($auth_email,$auth_password,$table)
    {
        $user = DB::table('users')->select()->where(['email'=>$auth_email,'password'=>$auth_password])->get();
        if((array)$user)
        {
            unset($user->password);
            Sessions::set($user->is_admin?'admin':'user',true,$user);
            Sessions::set('urole',true,$user->is_admin?"admin":"user");
            Sessions::set('uid',true,$user->id);
            return true;
        }else{
            return false;
        }
    }

    public static function logout()
    {
        Sessions::destroy('admin');
    }

    public static function uId()
    {
        return Sessions::get('uid');
    }

    public static function uRole()
    {
        return Sessions::get('urole');
    }
}