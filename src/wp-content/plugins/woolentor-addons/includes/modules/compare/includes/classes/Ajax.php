<?php
namespace EverCompare;
use WooLentor\Traits\Singleton;
/**
 * Ajax handlers class
 */
class Ajax {
    use Singleton;
    
    /**
     * Initialize the class
     */
    private function __construct() {

        // Add Ajax Callback
        add_action( 'wp_ajax_ever_compare_add_to_compare', [ $this, 'add_to_compare' ] );
        add_action( 'wp_ajax_nopriv_ever_compare_add_to_compare', [ $this, 'add_to_compare' ] );

        // Remove Ajax Callback
        add_action( 'wp_ajax_ever_compare_remove_from_compare', [ $this, 'remove_from_compare' ] );
        add_action( 'wp_ajax_nopriv_ever_compare_remove_from_compare', [ $this,'remove_from_compare' ] );

    }

    /**
     * [add_to_compare] Product add ajax callback
     */
    public function add_to_compare(){
        if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'compare_nonce' ) ){
            $errormessage = array(
                'message'  => __('Nonce Varification Faild !','woolentor')
            );
            wp_send_json_error( $errormessage );
        }
        $id = sanitize_text_field( $_GET['id'] );
        \EverCompare\Frontend\Manage_Compare::instance()->add_to_compare( $id );
    }

    /**
     * [remove_from_compare] Product delete ajax callback
     * @return [void]
     */
    public function remove_from_compare(){
        if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'compare_nonce' ) ){
            $errormessage = array(
                'message'  => __('Nonce Varification Faild !','woolentor')
            );
            wp_send_json_error( $errormessage );
        }
        $id = sanitize_text_field( $_GET['id'] );
        \EverCompare\Frontend\Manage_Compare::instance()->remove_from_compare( $id );
    }

}