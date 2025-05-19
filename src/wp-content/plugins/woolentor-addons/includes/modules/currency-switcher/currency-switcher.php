<?php
namespace Woolentor\Modules\CurrencySwitcher;
use WooLentor\Traits\ModuleBase;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Currency_Switcher{

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
        define( 'Woolentor\Modules\CurrencySwitcher\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\CurrencySwitcher\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\CurrencySwitcher\WIDGETS_PATH', MODULE_PATH. "/includes/widgets" );
        define( 'Woolentor\Modules\CurrencySwitcher\BLOCKS_PATH', MODULE_PATH. "/includes/blocks" );
        define( 'Woolentor\Modules\CurrencySwitcher\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\CurrencySwitcher\MODULE_ASSETS', MODULE_URL . '/assets' );
        define( 'Woolentor\Modules\CurrencySwitcher\ENABLED', self::$_enabled );
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
        require_once( MODULE_PATH. "/includes/classes/Widgets_And_Blocks.php" );

        // If Pro active
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') && defined( "WOOLENTOR_ADDONS_PL_PATH_PRO" ) ){
            if( file_exists(WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/currency-switcher/currency-switcher.php')){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/currency-switcher/currency-switcher.php' );
            }
        }
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
        // For Frontend
        if ( self::$_enabled && $this->is_request( 'frontend' ) ) {
            Frontend::instance();
        }

        // Register Widget and blocks
        if( self::$_enabled ){
            Widgets_And_Blocks::instance();
        }

    }

}