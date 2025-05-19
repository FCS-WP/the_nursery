<?php
namespace Woolentor\Modules\AdvancedCoupon;
use WooLentor\Traits\ModuleBase;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Advanced_Coupon{
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
        define( 'Woolentor\Modules\AdvancedCoupon\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\AdvancedCoupon\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\AdvancedCoupon\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\AdvancedCoupon\MODULE_ASSETS', MODULE_URL . '/assets' );
        define( 'Woolentor\Modules\AdvancedCoupon\ENABLED', self::$_enabled );
    }

    /**
     * Load Required File
     *
     * @return void
     */
    public function include(){
        require_once( MODULE_PATH. "/includes/Functions.php" );
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
        if ( $this->is_request( 'admin' ) ) {
            if( !$this->is_pro() && self::$_enabled ){
                add_action('woolentor_coupon_payment_fields',[Admin\Coupon_Meta_Boxes::instance(),'pro_payment_option_field'], 10, 1);
                add_action('woolentor_coupon_url_fields',[Admin\Coupon_Meta_Boxes::instance(),'pro_url_option_field'], 10, 1);
            }
        }

        if( self::$_enabled ){
            // For Frontend
            if ( $this->is_request( 'frontend' ) ) {
                Frontend::instance();
            }
        }

        // If is Active Pro.
        if( $this->is_pro() ){
            if( file_exists(WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/advanced-coupon/advanced-coupon.php')){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/advanced-coupon/advanced-coupon.php' );
            }
        }
        
    }

}