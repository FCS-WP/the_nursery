<?php
namespace Woolentor\Modules\Smart_Cross_Sell_Popup;
use WooLentor\Traits\ModuleBase;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Smart_Cross_Sell_Popup{

    use ModuleBase;

    /**
     * Constructor
     */
    public function __construct(){
        // Define constants
        $this->define_constants();

        // Include required files
        $this->include();

        // Initialize the module
        $this->init();
    }

    /**
     * Define Required Constants
     */
    public function define_constants(){
        define( 'Woolentor\Modules\Smart_Cross_Sell_Popup\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\Smart_Cross_Sell_Popup\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\Smart_Cross_Sell_Popup\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\Smart_Cross_Sell_Popup\MODULE_ASSETS', MODULE_URL . '/assets' );
        define( 'Woolentor\Modules\Smart_Cross_Sell_Popup\ENABLED', self::$_enabled );
    }

    /**
     * Include required files
     */
    public function include(){
        require_once( MODULE_PATH. "/includes/Functions.php" );
        require_once( MODULE_PATH. "/includes/classes/Admin.php" );
        require_once( MODULE_PATH. "/includes/classes/Frontend.php" );
    }

    /**
     * Initialize Module
     */
    public function init(){
        // For Admin
        if ( $this->is_request( 'admin' ) || $this->is_request( 'rest' ) ) {
            Admin::instance();
        }

        // For Frontend
        if( self::$_enabled ){
            if ( $this->is_request( 'frontend' ) ) {
                Frontend::instance();
            }
        }

        // Pro version integration
        if( $this->is_pro() ){
            if( file_exists(WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/smart-cross-sell-popup/smart-cross-sell-popup.php')){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/smart-cross-sell-popup/smart-cross-sell-popup.php' );
            }
        }
    }

}