<?php
namespace Woolentor\Modules\StoreVacation\Frontend;
use WooLentor\Traits\Singleton;
use \Woolentor\Modules\StoreVacation\Frontend as FrontendBase;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Manage_Vacation {
    use Singleton;

    /**
     * Constructor
     */

    public function __construct(){
        add_action('wp', [$this, 'init_hooks'], 999);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Initialize hooks
     */
    public function init_hooks(){
        if( !$this->is_vacation_active() ){
            return;
        }

        $notice_position = woolentor_get_option('notice_position', 'woolentor_store_vacation_settings');
        if( 'use_shortcode' != $notice_position ){
            if( 'shop_and_single_product' == $notice_position ){
                add_action('woocommerce_before_shop_loop', [$this, 'display_vacation_notice'], 10);
                add_action( 'woocommerce_before_single_product', [$this, 'display_vacation_notice'], 10 );
            }else{
                add_action($notice_position, [$this, 'display_vacation_notice']);
            }
        }
        
        if( woolentor_get_option('hide_add_to_cart', 'woolentor_store_vacation_settings') == 'on' ){
            add_filter('woocommerce_is_purchasable', [$this, 'handle_purchasable_status'], 10, 2);
            add_filter('woocommerce_get_availability_text', [$this, 'modify_availability_text'], 10, 2);
            add_action('woocommerce_single_product_summary', [$this, 'display_availability_notice'], 31);
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts(){
        if( !$this->is_vacation_active() ){
            return;
        }

        wp_add_inline_style('woocommerce-inline', $this->get_custom_styles());
    }

    /**
     * Get custom styles
     */
    private function get_custom_styles(){
        $notice_color = woolentor_get_option('notice_color', 'woolentor_store_vacation_settings');
        $notice_bg_color = woolentor_get_option('notice_bg_color', 'woolentor_store_vacation_settings');

        return "
            .woolentor-store-vacation-notice {
                color: {$notice_color};
                background-color: {$notice_bg_color};
                padding: 15px;
                margin-bottom: 20px;
                font-size: 16px;
            }
            .woolentor-product-availability-notice {
                color: {$notice_color};
                margin-top: 10px;
            }
        ";
    }

    /**
     * Display vacation notice
     */

    public function display_vacation_notice(){
        $message = woolentor_get_option('vacation_message', 'woolentor_store_vacation_settings');
        $message = apply_filters( 'woolentor_vacation_notice_message', $message );
        if( !empty($message) ){
            $prepare_message = FrontendBase::process_message_placeholders($message);
            $notice = sprintf('<div class="woolentor-store-vacation-notice">%s</div>',wp_kses_post( $prepare_message ));
            echo apply_filters( 'woolentor_vacation_notice_content', $notice, $prepare_message );
        }
    }

    /**
     * Modify product availability text
     */
    public function modify_availability_text($availability, $product){
        if( $this->is_vacation_active() ){
            $availability = woolentor_get_option('product_availability_text', 'woolentor_store_vacation_settings');
        }
        return $availability;
    }

    /**
     * Display availability notice on product page
     */
    public function display_availability_notice(){
        if( $this->is_vacation_active() ){
            $text = woolentor_get_option('product_availability_text', 'woolentor_store_vacation_settings');
            echo '<div class="woolentor-product-availability-notice">' . esc_html($text) . '</div>';
        }
    }

    /**
     * Handle product purchasable status
     */
    public function handle_purchasable_status($purchasable, $product){
        if( $this->is_vacation_active() ){
            return false;
        }
        return $purchasable;
    }

    /**
     * Check if vacation is active
     */
    private function is_vacation_active(){
        return FrontendBase::is_vacation_active();
    }


}