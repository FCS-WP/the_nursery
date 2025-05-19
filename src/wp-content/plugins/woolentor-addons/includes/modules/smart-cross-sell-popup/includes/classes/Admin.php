<?php
namespace Woolentor\Modules\Smart_Cross_Sell_Popup;
use WooLentor\Traits\Singleton;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Admin {
    use Singleton;

    /**
     * Constructor
     */
    public function __construct(){
        $this->includes();
        $this->init();
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