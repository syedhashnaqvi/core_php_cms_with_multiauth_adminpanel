<?php
namespace App\Site;
include_once 'Request.php';
use root\DB;
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
            Sessions::session()->create_session('flash_message',true,['type'=>'danger','msg'=>'LOGIN ATTEMPT FAILED <br> Please check user name and password!']);
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