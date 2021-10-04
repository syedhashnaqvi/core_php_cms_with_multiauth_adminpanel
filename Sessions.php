<?php
namespace root;
class Sessions {

    protected $sessionID;
    private static $instance;
    
    public function __construct(){
        session_start();
    }

    public static function session()
    {
        if ( is_null( self::$instance ) )
        {
        self::$instance = new self();
        }
        return self::$instance;
    }
    
    public static function init_session(){
        session_start();
    }
    
    public function set_session_id(){
        //$this->start_session();
        $this->sessionID = session_id();
    }
    
    public function get_session_id(){
        return $this->sessionID;
    }
    
    public function session_exist( $session_name ){
        return isset($_SESSION[$session_name]);
    }
    
    public function create_session( $session_name , $force = false ,$data){
        if( !self::session_exist($session_name) || $force ){
            $_SESSION[$session_name] = $data;          
        }
    }
    
    public function display_session( $session_name = null ){
        echo '<pre>';$session_name==null?print_r($_SESSION):print_r($_SESSION[$session_name]);echo '</pre>';
    }
    
    public function remove_session( $session_name = '' ){
        if( !empty($session_name) ){
            unset( $_SESSION[$session_name] );
        }
        else{
            unset($_SESSION);
        }
    }
    
    public function get_session_data( $session_name ){
        return $_SESSION[$session_name];
    }
    
    public function set_session_data( $session_name , $data ){
        $_SESSION[$session_name] = $data;
    }

    public function flash_message(){
        if(self::session_exist('flash_message')  ){
            $data = self::get_session_data('flash_message');
            echo '<div class="alert alert-'.$data['type'].'" role="alert">
                    '.$data['msg'].'
                </div>';
        }

        self::remove_session('flash_message');

    }
    
}