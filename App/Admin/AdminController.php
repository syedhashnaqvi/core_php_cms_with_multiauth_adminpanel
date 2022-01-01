<?php
namespace App\Admin;
include_once 'Request.php';
use root\DB;
use root\Sessions;
use App\Auth;
use Template;

class AdminController{
    public function __construct(){
       
    }
    
    public function login($request){
        $template = new Template('admin/login');
        $template->title('Admin - Login');
        $template->request($request);

        print $template;
    }

    public function login_verify($request)
    {
        $data = $request->getBody();
        $auth = Auth::attemptLogin($data['email'],$data['password'],'users');
        if($auth) {
            redirect('/admin');
        }else{
            Sessions::set('flash_message',true,['type'=>'danger','msg'=>'LOGIN ATTEMPT FAILED <br> Please check user name and password!']);
            redirect('/admin');
        }

    }

    public function logout()
    {
        Sessions::destroy('admin');
        redirect('/admin');
    }

    public function dashboard($request)
    {
        $template = new Template('admin/dashboard');
        $template->title('Home - Admin');
        $template->request($request);
        print $template;
    }

}