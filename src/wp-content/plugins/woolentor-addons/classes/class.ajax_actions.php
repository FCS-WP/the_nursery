<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Ajax_Action{

	/**
	 * [$instance]
	 * @var null
	 */
	private static $instance = null;

	/**
	 * [instance]
	 * @return [Woolentor_Ajax_Action]
	 */
    public static function instance(){
        if( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct]
     */
    function __construct(){

        // For Ajax Add to cart
		add_action( 'wp_ajax_woolentor_insert_to_cart', [ $this, 'insert_to_cart' ] );
		add_action( 'wp_ajax_nopriv_woolentor_insert_to_cart', [ $this, 'insert_to_cart' ] );

        // For Single Product ajax add to cart
        add_action( 'wp_ajax_woolentor_add_to_cart_single_product', [ $this, 'add_to_cart_from_single_product' ] );
		add_action( 'wp_ajax_nopriv_woolentor_add_to_cart_single_product', [ $this, 'add_to_cart_from_single_product' ] );

        // Sugest Price Elementor addon
        add_action( 'wp_ajax_woolentor_suggest_price_action', [$this, 'suggest_price'] );
        add_action( 'wp_ajax_nopriv_woolentor_suggest_price_action', [$this, 'suggest_price'] );

    }

    /**
     * [insert_to_cart] Insert add to cart
     * @return [JSON]
     */
    public function insert_to_cart(){

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'woolentor_psa_nonce' ) ){
            $errormessage = array(
                'message'  => __('Nonce Varification Faild !','woolentor')
            );
            wp_send_json_error( $errormessage );
        }

        // phpcs:disable WordPress.Security.NonceVerification.Missing
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }

        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        $quantity           = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
        $variation_id       = !empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        $variations         = !empty( $_POST['variations'] ) ? array_map( 'sanitize_text_field', $_POST['variations'] ) : array();
        $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
        $product_status     = get_post_status( $product_id );

        if ( $passed_validation && \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) && 'publish' === $product_status ) {
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
            \WC_AJAX::get_refreshed_fragments();
        } else {
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            );
            wp_send_json_error( $data );
        }
        wp_send_json_success();
        
    }

    /**
     * Ajax Actin For Single Product Add to Cart
     */
    public function add_to_cart_from_single_product() {

        add_action( 'wp_loaded', [ 'WC_Form_Handler', 'add_to_cart_action' ], 20 );

        $wc_notice = wc_get_notices();
        if ( is_callable( [ 'WC_AJAX', 'get_refreshed_fragments' ] ) && ! isset( $wc_notice['error'] ) ) {
            \WC_AJAX::get_refreshed_fragments();
        }

        wp_send_json_success();
    }

    /**
     * [single_product_insert_to_cart] Single product ajax add to cart callable function
     * @return [JSON]
     * @todo Delete After 2 Majon Release
     */
    public function single_product_insert_to_cart(){
        
        // phpcs:disable WordPress.Security.NonceVerification.Missing
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }

        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        $product            = wc_get_product( $product_id );
        $quantity           = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
        $variation_id       = !empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        $variations         = !empty( $_POST['variations'] ) ? array_map( 'sanitize_text_field', json_decode( stripslashes( $_POST['variations'] ), true ) ) : array();
        $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
        $product_status     = get_post_status( $product_id );

        $cart_item_data = $_POST['alldata'];

        if ( $passed_validation && 'publish' === $product_status ) {

            if( count( $variations ) == 0 ){
                \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations, $cart_item_data );
            }

            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
                wc_add_to_cart_message( [ $product_id => $quantity ], true );
            }
            \WC_AJAX::get_refreshed_fragments();
        } else {
            $data = [
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            ];
            wp_send_json_error( $data );
        }
        wp_send_json_success();
        
    }

    /**
     * Email Send for suggest_price
     * @return [void]
     */
    public function suggest_price(){
        $response = [
            'error' => false,
        ];

        if ( !isset( $_POST['woolentor_suggest_price_nonce_field'] ) || !wp_verify_nonce( $_POST['woolentor_suggest_price_nonce_field'], 'woolentor_suggest_price_nonce' ) ){

            $response['error'] = true;
            $response['message'] = esc_html__('Sorry, your nonce verification fail.','woolentor');

            wp_send_json_error( $response );

        }else{

            $sent_to        = $_POST['send_to'];
            $product_title  = $_POST['product_title'];
            $msg_success    = $_POST['msg_success'];
            $msg_error      = $_POST['msg_error'];
            $name           = $_POST['wlname'];
            $email          = trim($_POST['wlemail']);
            $message        = $_POST['wlmessage'];

            if ( $email == '' ) {
                $response['error'] = true;
                $response['message'] = esc_html__('Email is required.','woolentor');
        
                wp_send_json_error( $response );
            }

            if ( $message == '' ) {
                $response['error'] = true;
                $response['message'] = esc_html__('Message is required.','woolentor');
        
                wp_send_json_error( $response );
            }

            //php mailer variables
            $subject = esc_html__("Suggest Price For - ".$product_title, "woolentor");
            $headers = esc_html__('From: ','woolentor'). esc_html( $email ) . "\r\n" . esc_html__('Reply-To: ', 'woolentor') . esc_html( $email ) . "\r\n";

            // Here put your Validation and send mail
            $mail_sent_status = wp_mail( $sent_to, $subject, wp_strip_all_tags($message), $headers );

            if( $mail_sent_status ) {
                $response['error'] = false;
                $response['message'] = esc_html( $msg_success );
            }
            else{
                $response['error'] = true;
                $response['message'] = esc_html( $msg_error );
            }

            wp_send_json_success( $response );

        }



    }
    

}

Woolentor_Ajax_Action::instance();
