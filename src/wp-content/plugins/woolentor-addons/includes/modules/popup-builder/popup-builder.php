<?php
namespace Woolentor\Modules\Popup_Builder;
use Woolentor\Modules\Popup_Builder\Frontend;
use WooLentor\Traits\Singleton;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Popup_Builder{
    use Singleton;

    /**
     * Constructor
     */
    function __construct(){
        $this->define_constants();
        $this->includes();
        $this->initialize();
    }

    /**
     * Define the required constants.
     */
    private function define_constants() {
        define( 'Woolentor\Modules\Popup_Builder\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\Popup_Builder\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\Popup_Builder\WIDGETS_PATH', MODULE_PATH . '/includes/widgets' );
        define( 'Woolentor\Modules\Popup_Builder\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\Popup_Builder\MODULE_ASSETS', MODULE_URL . '/assets' );
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    private function includes() {
        spl_autoload_register( array( $this, 'autoloader' ) );

        // If Pro Active
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') && defined( "WOOLENTOR_ADDONS_PL_PATH_PRO" ) ){
            $popup_builder_pro_module_file = WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/popup-builder-pro/class-popup-builder-pro.php';
            if( (  file_exists($popup_builder_pro_module_file) )){
                require_once( $popup_builder_pro_module_file );
            }
        }

    }

    /**
     * Autoloader.
     */
    private function autoloader( $class ) {
        if ( 0 === strpos( $class, 'Woolentor\Modules\Popup_Builder' ) ) {

            // Replace the namespace prefix with includes directory and change the _ to -.
            $file = str_replace( array('Woolentor\Modules\Popup_Builder', '_'), array('includes', '-'), $class );

            // Add class- prefix to the filename.
            $file_arr = explode('\\', $file);
            if( !empty($file_arr) ){
                $file = str_replace( end($file_arr), 'class-'. end($file_arr),  $file);
            }

            // Convert the filename to lowercase and replace the namespace separator with directory separator.
            $file = str_replace( array( '\\', ), array( DIRECTORY_SEPARATOR, ), strtolower($file) );
            $file = sprintf( '%1$s%2$s.php', trailingslashit( MODULE_PATH ), $file );
            
            $file = realpath( $file );

            // If the file exists, require it.
            if ( false !== $file && file_exists( $file ) ) {
                require $file;
            }
        }
    }

    /**
     * Initialize.
     */
    private function initialize() {
        new Ajax_Actions();
        new Assets();
        new Shortcodes();
        
        new Admin\Manage_Post_Type();
        new Admin\Manage_Metabox();
        new Frontend\Manage_Popup();
        new Widgets();

        // If elementor plugin is active.
        if( class_exists('Elementor\Plugin') ){
            new Frontend\Manage_Elementor_Editor();
        }
    }
    
}

Popup_Builder::instance();