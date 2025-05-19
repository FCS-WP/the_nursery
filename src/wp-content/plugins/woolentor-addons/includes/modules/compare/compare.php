<?php
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Plugin Main Class
*/
class Woolentor_Ever_Compare{
    use Singleton;
    
    /**
     * [__construct] Class Constructor
     */
    private function __construct(){

        $this->define_constants();
        $this->includes();
        if( get_option('woolentor_compare_status', 'no') === 'no' ){
            add_action( 'wp_loaded',[ $this, 'activate' ] );
            update_option( 'woolentor_compare_status','yes' );
        }
        $this->init_plugin();

    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'EVERCOMPARE_FILE', __FILE__ );
        define( 'EVERCOMPARE_MODULE_PATH', __DIR__ );
        define( 'EVERCOMPARE_DIR', plugin_dir_path( EVERCOMPARE_FILE ) );
        define( 'EVERCOMPARE_URL', plugins_url( '', EVERCOMPARE_FILE ) );
        define( 'EVERCOMPARE_ASSETS', EVERCOMPARE_URL . '/assets' );
        define( 'EVERCOMPARE_BLOCKS_PATH', EVERCOMPARE_MODULE_PATH. "/includes/blocks" );
    }

     /**
     * [includes] Load file
     * @return [void]
     */
    public function includes(){
        if ( !function_exists('wp_strip_all_tags') ) {
            require_once( ABSPATH . 'wp-includes/formatting.php' );
        }
        require_once(__DIR__ . '/includes/classes/Installer.php');
        require_once(__DIR__ . '/includes/helper-functions.php');
        require_once(__DIR__ . '/includes/classes/Assets.php');
        require_once(__DIR__ . '/includes/classes/Admin.php');
        require_once(__DIR__ . '/includes/classes/Frontend.php');
        require_once(__DIR__ . '/includes/classes/Ajax.php');
        require_once(__DIR__ . '/includes/classes/Widgets_And_Blocks.php');
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {

        EverCompare\Assets::instance();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            EverCompare\Ajax::instance();
        }

        if ( is_admin() ) {
            EverCompare\Admin::instance();
        }
        EverCompare\Frontend::instance();
        EverCompare\Widgets_And_Blocks::instance();

        // add image size
        $this->set_image_size();

        // let's filter the woocommerce image size
        add_filter( 'woocommerce_get_image_size_ever-compare-image', [ $this, 'wc_image_filter_size' ], 10, 1 );

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installer = new EverCompare\Installer();
        $installer->run();
    }

    /**
     * [set_image_size] Set Image Size
     */
    public function set_image_size(){

        $image_dimention = woolentor_get_option( 'image_size', 'ever_compare_table_settings_tabs', array( 'width'=>300, 'height'=>300 ) );
        if( isset( $image_dimention ) && is_array( $image_dimention ) ){
            $hard_crop = !empty( woolentor_get_option( 'hard_crop', 'ever_compare_table_settings_tabs' ) ) ? true : false;
            add_image_size( 'ever-compare-image', $image_dimention['width'], $image_dimention['height'], $hard_crop );
        }

    }

    /**
     * [wc_image_filter_size]
     * @return [array]
     */
    public function wc_image_filter_size(){

        $image_dimention = woolentor_get_option( 'image_size', 'ever_compare_table_settings_tabs', array('width'=>300,'height'=>300) );
        $hard_crop = !empty( woolentor_get_option( 'hard_crop', 'ever_compare_table_settings_tabs' ) ) ? true : false;

        if( isset( $image_dimention ) && is_array( $image_dimention ) ){
            return array(
                'width'  => isset( $image_dimention['width'] ) ? absint( $image_dimention['width'] ) : 300,
                'height' => isset( $image_dimention['height'] ) ? absint( $image_dimention['height'] ) : 300,
                'crop'   => isset( $hard_crop ) ? 1 : 0,
            );
        }
        
    }

}

/**
 * Initializes the main plugin
 *
 * @return Woolentor_Ever_Compare
 */
function woolentor_ever_compare() {
    return Woolentor_Ever_Compare::instance();
}
if( ! class_exists('Ever_Compare') ){
    woolentor_ever_compare();
}