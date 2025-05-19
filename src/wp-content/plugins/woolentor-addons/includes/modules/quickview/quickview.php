<?php
namespace Woolentor\Modules\QuickView;
use WooLentor\Traits\ModuleBase;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Quick_View{
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

        // Block Assets
        add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );

    }

    /**
     * Defined Required Constants
     *
     * @return void
     */
    public function define_constants(){
        define( 'Woolentor\Modules\QuickView\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\QuickView\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\QuickView\WIDGETS_PATH', MODULE_PATH. "/includes/widgets" );
        define( 'Woolentor\Modules\QuickView\BLOCKS_PATH', MODULE_PATH. "/includes/blocks" );
        define( 'Woolentor\Modules\QuickView\TEMPLATE_PATH', MODULE_PATH. "/includes/templates/" );
        define( 'Woolentor\Modules\QuickView\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\QuickView\MODULE_ASSETS', MODULE_URL . '/assets' );
        define( 'Woolentor\Modules\QuickView\ENABLED', self::$_enabled );
    }

    /**
     * Load Required File
     *
     * @return void
     */
    public function include(){
        require_once( MODULE_PATH. "/includes/helper-functions.php" );
        require_once( MODULE_PATH. "/includes/classes/Admin.php" );
        require_once( MODULE_PATH. "/includes/classes/Frontend.php" );
        require_once( MODULE_PATH. "/includes/classes/Assets.php" );
        require_once( MODULE_PATH. "/includes/classes/Ajax.php" );

        // If Pro Active
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') && defined( "WOOLENTOR_ADDONS_PL_PATH_PRO" ) && self::$_enabled){
            if( file_exists(WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/quickview/quickview.php')){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/quickview/quickview.php' );
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
        if ( $this->is_request( 'admin' ) || $this->is_request( 'rest' )) {
            Admin::instance();
        }

        if( self::$_enabled ){
            // Assets
            if ( $this->is_request( 'frontend' ) ) {
                Assets::instance();
            }

            // Ajax Action
            if ( $this->is_request( 'ajax' ) ) {
                Ajax::instance();
            }

            // For Frontend
            if ( $this->is_request( 'frontend' ) ) {
                Frontend::instance();
            }
        }

    }

    /**
	 * Block editor assets.
	 */
	public function block_editor_assets() {
        wp_enqueue_style('woolentor-quickview', MODULE_ASSETS . '/css/frontend.css', [], WOOLENTOR_VERSION );
    }

}