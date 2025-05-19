<?php
namespace Woolentor\Modules\CurrencySwitcher;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Frontend handlers class
 */
class Frontend {
    use Singleton;
    
    /**
     * Initialize the class
     */
    private function __construct() {
        $this->includes();
        $this->init();
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'wp_ajax_woolentor_save_current_currency',[ $this, 'save_current_currency' ] );
        add_action( 'wp_ajax_nopriv_woolentor_save_current_currency', [ $this, 'save_current_currency' ] );
    }

    /**
     * Load Required files
     *
     * @return void
     */
    private function includes(){
        require_once( __DIR__. '/Frontend/Manage_price.php' );
        require_once( __DIR__. '/Frontend/Shortcode.php' );
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function init(){
        Frontend\Manage_Price::instance();
        Frontend\Shortcode::instance();
    }

    /**
     * Enqueue Scripts
     *
     * @return void
     */
    public function enqueue_scripts(){
        wp_enqueue_style('woolentor-currency-switcher', MODULE_ASSETS . '/css/frontend.css', [], WOOLENTOR_VERSION );
        
        wp_enqueue_script('woolentor-currency-switcher', MODULE_ASSETS . '/js/frontend.js', ['jquery'], WOOLENTOR_VERSION, true );
        wp_localize_script( 'woolentor-currency-switcher', 'wlsl_currency_switcher', [
            'ajaxUrl'=> admin_url('admin-ajax.php'),
            'nonce'  => wp_create_nonce('woolentor_cs_nonce'),
        ] );
    }

    /**
     * Save Current Currency
     *
     * @return void
     */
    public function save_current_currency(){
        
		if ( ! isset( $_POST['wpnonce'] ) || ! wp_verify_nonce( $_POST['wpnonce'], 'woolentor_cs_nonce' ) ){
            $errormessage = array(
                'message'  => __('Nonce Varification Faild !','woolentor-pro')
            );
            wp_send_json_error( $errormessage );
        }

	    $current_user_id = get_current_user_id();
	    $currency_code 	 = sanitize_text_field( $_POST['data'] );

	    if ( $current_user_id ) {
			update_user_meta( $current_user_id, 'woolentor_current_currency_code', $currency_code );
	    }
	    else {
			setcookie( 'woolentor_current_currency_code', $currency_code, date_i18n( 'U' ) + 86400, COOKIEPATH, COOKIE_DOMAIN );
	    }

	    wp_send_json_success( __('Currency Switched', 'woolentor-pro') );

    }


}