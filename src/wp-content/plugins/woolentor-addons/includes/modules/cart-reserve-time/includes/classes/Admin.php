<?php
namespace Woolentor\Modules\CartReserveTime;
use WooLentor\Traits\Singleton;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Admin handlers class
 */
class Admin{
    use Singleton;

    /**
     * Constructor
     */
    public function __construct(){
        $this->includes();
        add_action( 'admin_init', [ $this, 'init' ] );
    }

    /**
     * Load Required files
     *
     * @return void
     */
    private function includes(){
        require_once( __DIR__. '/Admin/Fields.php' );
    }

    /**
     * Initialize
     */
    public function init(){
        Admin\Fields::instance();
    }

}