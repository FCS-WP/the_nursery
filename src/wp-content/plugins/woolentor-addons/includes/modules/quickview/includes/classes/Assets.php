<?php
namespace Woolentor\Modules\QuickView;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Assets handlers class
 */
class Assets {
    use Singleton;
    
    /**
     * Class construct
     */
    private function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
    }


    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {

        $script_list = [
            'woolentor-quickview' => [
                'src'     => MODULE_ASSETS . '/js/frontend.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery', 'wc-add-to-cart-variation', 'wc-single-product' ]
            ],
        ];

        if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
            array_push( $script_list['woolentor-quickview']['deps'], 'zoom' );
        }

        if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
            array_push( $script_list['woolentor-quickview']['deps'],'flexslider' );
        }

        if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
            array_push( $script_list['woolentor-quickview']['deps'],'photoswipe-ui-default' );
        }

        return $script_list;

    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {

        $style_list = [
            'woolentor-quickview' => [
                'src'     => MODULE_ASSETS . '/css/frontend.css',
                'version' => WOOLENTOR_VERSION,
                'deps'    => []
            ],
        ];

        if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
            array_push( $style_list['woolentor-quickview']['deps'],'photoswipe-default-skin' );
        }

        return $style_list;

    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;
            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        // Frontend Localize data
        $option_data = [
            'enableAjaxCart'  => woolentor_get_option( 'enable_ajax_cart','woolentor_quickview_settings','on' ),
            'thumbnailLayout' => woolentor_get_option( 'thumbnail_layout','woolentor_quickview_settings','slider' ),
        ];
        $localize_data = [
            'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
            'ajaxNonce'  => wp_create_nonce( 'woolentor_quickview_nonce' ),
            'optionData' => $option_data,
        ];

        wp_localize_script( 'woolentor-quickview', 'woolentorQuickView', $localize_data );
        
    }

   

}