<?php
namespace root;
class Sessions {

    private static $instance;
    
    public function __construct(){
        session_start();
    }

    public static function init(){
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public static function exist( $session_name ){
        self::init();
        return isset($_SESSION[$session_name]);
    }
    
    public static function set( $session_name , $force = false ,$data){
        self::init();
        if( !self::exist($session_name) || $force ){
            $_SESSION[$session_name] = $data;          
        }
    }
    
    public static function view_session( $session_name = null ){
        self::init();
        echo '<pre>';$session_name==null?print_r($_SESSION):print_r($_SESSION[$session_name]);echo '</pre>';
    }
    
    public static function destroy( $session_name = '' ){
        self::init();
        if( !empty($session_name) ){
            unset( $_SESSION[$session_name] );
        }
        else{
            unset($_SESSION);
        }
    }
    
    public static function get( $session_name ){
        self::init();
        return isset($_SESSION[$session_name]) ? $_SESSION[$session_name] : NULL;
    }

    public static function flash_message(){
        self::init();
        if(self::exist('flash_message')  ){
            $data = self::get_session_data('flash_message');
            echo '<div class="alert alert-'.$data['type'].'" role="alert">
                    '.$data['msg'].'
                </div>';
        }

        self::destroy('flash_message');
    }
}