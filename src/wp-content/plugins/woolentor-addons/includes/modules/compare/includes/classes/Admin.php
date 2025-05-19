<?php
namespace EverCompare;
use WooLentor\Traits\Singleton;

/**
 * Admin handlers class
 */
class Admin {
    use Singleton;
    
    /**
     * Initialize the class
     */
    private function __construct() {
        require_once( __DIR__. '/Admin/Dashboard.php' );
        Admin\Dashboard::instance();
    }

}