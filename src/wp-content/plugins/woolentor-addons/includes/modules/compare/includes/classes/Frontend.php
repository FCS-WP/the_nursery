<?php
namespace EverCompare;
use WooLentor\Traits\Singleton;

/**
 * Frontend handlers class
 */
class Frontend {
    use Singleton;
    
    /**
     * Initialize the class
     */
    private function __construct() {
        $this->includes();
        Frontend\Manage_Compare::instance();
        Frontend\Shortcode::instance();
    }

    public function includes(){
        require_once( __DIR__. '/Frontend/Manage_Compare.php' );
        require_once __DIR__ . '/Frontend/Shortcode.php';
    }

}