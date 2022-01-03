<?php

namespace App;
use Core\DB;
use Core\Sessions;
use Core\Hash;

class Auth
{

    public static function attemptLogin($auth_email,$auth_password,$table)
    {
        $user = DB::table('users')->select()->where(['email'=>$auth_email])->get();
        if((array)$user)
        {
            if(!Hash::verifyHash($auth_password,$user->password)){
                return false;
            }
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

    public static function user()
    {
        return Sessions::get(self::uRole());
    }
}