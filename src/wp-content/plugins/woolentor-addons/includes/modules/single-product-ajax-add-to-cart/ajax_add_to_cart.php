<?php
namespace WooLentor;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*  Single product Ajax add to cart
*/
class Single_Product_Ajax_Add_To_Cart{
    use Singleton;
    
    function __construct(){
        if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        }
    }

    // Ajax Cart Script
    public function enqueue_scripts(){
        global $post;
        if( function_exists( 'is_product' ) && is_product() ){
            $product = wc_get_product( $post->ID );
            if ( ( $product->is_type( 'simple' ) || $product->is_type( 'variable' ) || $product->is_type('grouped') ) ) {
                wp_localize_script( 'jquery-single-product-ajax-cart', 'WLSPL', [ 'ajax_url'=> admin_url( 'admin-ajax.php' )] );
                wp_enqueue_script( 'jquery-single-product-ajax-cart' );
            }
        }
    }

}

Single_Product_Ajax_Add_To_Cart::instance();
