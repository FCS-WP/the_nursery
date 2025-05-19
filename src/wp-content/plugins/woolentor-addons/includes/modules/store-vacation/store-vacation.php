<?php
namespace Woolentor\Modules\StoreVacation;
use WooLentor\Traits\ModuleBase;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Store_Vacation{

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
        define( 'Woolentor\Modules\StoreVacation\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\StoreVacation\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\StoreVacation\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\StoreVacation\WIDGETS_PATH', MODULE_PATH. "/includes/widgets" );
        define( 'Woolentor\Modules\StoreVacation\BLOCKS_PATH', MODULE_PATH. "/includes/blocks" );
        define( 'Woolentor\Modules\StoreVacation\MODULE_ASSETS', MODULE_URL . '/assets' );
        define( 'Woolentor\Modules\StoreVacation\ENABLED', self::$_enabled );
    }

    /**
     * Include required files
     */
    public function include(){
        require_once( MODULE_PATH. "/includes/Functions.php" );
        require_once( MODULE_PATH. "/includes/classes/Admin.php" );
        require_once( MODULE_PATH. "/includes/classes/Frontend.php" );
        require_once( MODULE_PATH. "/includes/classes/Widgets_And_Blocks.php" );
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

            // Register Widget and blocks
            Widgets_And_Blocks::instance();
        }

        // Pro version integration
        if( $this->is_pro() ){
            if( file_exists(WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/store-vacation/store-vacation.php')){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/store-vacation/store-vacation.php' );
            }
        }

    }

}