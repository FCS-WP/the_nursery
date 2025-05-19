<?php
namespace Woolentor\Modules\QuickView;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
        $this->init();
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Load Required files
     *
     * @return void
     */
    private function includes(){
        require_once( __DIR__. '/Frontend/Popup_Manager.php' );
        require_once( __DIR__. '/Frontend/Shortcode.php' );
        require_once( __DIR__. '/Frontend/Button_Manager.php' );
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function init(){
        Frontend\Popup_Manager::instance();
        Frontend\Shortcode::instance();
        Frontend\Button_Manager::instance();
    }

    /**
     * Enqueue Scripts
     *
     * @return void
     */
    public function enqueue_scripts(){
        wp_enqueue_style('woolentor-quickview');
        wp_enqueue_script('slick');
        wp_enqueue_script('woolentor-quickview');
    }

    


}