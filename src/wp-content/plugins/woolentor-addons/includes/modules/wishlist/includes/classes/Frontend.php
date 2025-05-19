<?php
namespace WishSuite;
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
        Frontend\Shortcode::instance();
        Frontend\Manage_Wishlist::instance();
    }

    public function includes(){
        require_once( __DIR__. '/Frontend/Manage_Wishlist.php' );
        require_once __DIR__ . '/Frontend/Shortcode.php';
    }

}