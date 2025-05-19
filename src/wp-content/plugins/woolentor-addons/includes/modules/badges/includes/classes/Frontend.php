<?php
namespace Woolentor\Modules\Badges;
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
        require_once( __DIR__. '/Frontend/Badge_Manager.php' );
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function init(){
        Frontend\Badge_Manager::instance();
    }

    /**
     * Enqueue Scripts
     *
     * @return void
     */
    public function enqueue_scripts(){
        wp_enqueue_style('woolentor-product-badge', MODULE_ASSETS . '/css/frontend.css', [], WOOLENTOR_VERSION );
    }

    


}