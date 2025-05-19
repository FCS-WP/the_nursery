<?php
namespace Woolentor\Modules\CartReserveTime;
use WooLentor\Traits\ModuleBase;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Cart_Reserve_Time{
    use ModuleBase;

    /**
     * Class Constructor
     */
    public function __construct(){

        // Definded Constants
        $this->define_constants();

        // Include Nessary file
        $this->include();

        // initialize
        $this->init();

    }

    /**
     * Defined Required Constants
     *
     * @return void
     */
    public function define_constants(){
        define( 'Woolentor\Modules\CartReserveTime\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\CartReserveTime\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\CartReserveTime\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\CartReserveTime\MODULE_ASSETS', MODULE_URL . '/assets' );
        define( 'Woolentor\Modules\CartReserveTime\ENABLED', self::$_enabled );
    }

    /**
     * Load Required File
     *
     * @return void
     */
    public function include(){
        require_once( MODULE_PATH. "/includes/classes/Admin.php" );
        require_once( MODULE_PATH. "/includes/classes/Frontend.php" );
    }

    /**
     * Module Initilize
     *
     * @return void
     */
    public function init(){
        // For Admin
        if ( $this->is_request( 'admin' ) || $this->is_request( 'rest' ) ) {
            Admin::instance();
        }

        if( self::$_enabled ){
            // For Frontend
            if ( $this->is_request( 'frontend' ) ) {
                Frontend::instance();
            }

        }

        // If is Active Pro.
        if( $this->is_pro() ){
            if( file_exists(WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/cart-reserve-time/cart-reserve-time.php')){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/cart-reserve-time/cart-reserve-time.php' );
            }
        }
    }

}