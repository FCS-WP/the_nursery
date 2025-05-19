<?php
namespace Woolentor\Modules\QuickView;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Ajax handlers class
 */
class Ajax {
    use Singleton;
    
    /**
     * Class construct
     */
    private function __construct() {

        // Ajax Action for QuickView Open
        add_action( 'wp_ajax_woolentor_quickview', [ $this, 'quickview_ajax' ] );
        add_action( 'wp_ajax_nopriv_woolentor_quickview', [ $this, 'quickview_ajax' ] );

        // For Add to cart
        if( woolentor_get_option( 'enable_ajax_cart','woolentor_quickview_settings','on' ) === 'on' ){
            add_action( 'wp_ajax_woolentor_quickview_insert_to_cart', [ $this, 'insert_to_cart' ] );
            add_action( 'wp_ajax_nopriv_woolentor_quickview_insert_to_cart', [ $this, 'insert_to_cart' ] );
        }

    }

    // QuickView Ajax Action
    public function quickview_ajax() {
        check_ajax_referer( 'woolentor_quickview_nonce', 'nonce' );

        if ( isset( $_POST['id'] ) && (int) $_POST['id'] ) {
            global $post, $product, $woocommerce;
            $id      = sanitize_text_field( (int) $_POST['id'] );
            $post    = get_post( $id );
            $product = wc_get_product( $id );
            if ( $product && is_a( $product, 'WC_Product' ) ) {
                if( $product->get_catalog_visibility() === 'hidden'){
                    wp_die( -1, 403 );
                }
                echo "<div class='woolentorquickview-content-template ".$product->get_type()."'>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                include ( apply_filters( 'woolentor_quickview_tmp', TEMPLATE_PATH.'quickview-content.php' ) ); 
                echo "</div>";
            }
        }
        wp_die();
    }

    /**
     * [insert_to_cart] Insert product
     * @return [JSON]
     */
    public function insert_to_cart(){

        check_ajax_referer( 'woolentor_quickview_nonce', 'nonce' );

        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }

        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        $quantity           = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
        $variation_id       = !empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        $variations         = !empty( $_POST['variations'] ) ? array_map( 'sanitize_text_field', json_decode( stripslashes( $_POST['variations'] ), true ) ) : [];
        $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
        $product_status     = get_post_status( $product_id );

        $cart_item_data = $_POST;

        if ( $passed_validation && 'publish' === $product_status ) {

            if( count( $variations ) == 0 ){
                \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations, $cart_item_data );
            }

            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
                wc_add_to_cart_message( [$product_id => $quantity], true );
            }
            \WC_AJAX::get_refreshed_fragments();
        } else {
            $data = [
                'error'       => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            ];
            echo wp_send_json( $data );
        }
        wp_send_json_success();

        
    }
    

}