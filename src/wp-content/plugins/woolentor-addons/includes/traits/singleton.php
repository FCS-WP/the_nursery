<?php
namespace WooLentor\Traits;

trait Singleton {

    private static $_instance = null;
    /**
     * Get Instance
     */
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }


}
