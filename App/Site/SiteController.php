<?php
namespace App\Site;
include_once 'Request.php';
use Core\DB;
use Core\Hash;
use Template;

class SiteController{
    
    public function login($request){
        $template = new Template('site/login');
        $template->title('User - Login');
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
            Sessions::set('flash_message',['type'=>'danger','msg'=>'LOGIN ATTEMPT FAILED <br> Please check user name and password!'],true);
            redirect('/admin');
        }

    }

    public function homePage($request)
    {
        $abc = "this is a var";
        $template = new Template('site/homepage');
        $template->title('Home - Site');
        $template->data($abc);

        print $template;
    }

    public function profile(){
        echo "profile page";
    }
}