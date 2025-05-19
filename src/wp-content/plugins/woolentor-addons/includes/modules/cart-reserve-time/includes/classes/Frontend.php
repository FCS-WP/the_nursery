<?php
namespace Woolentor\Modules\CartReserveTime;
use WooLentor\Traits\Singleton;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Frontend{
    use Singleton;

    /**
     * Constructor
     */
    public function __construct(){
        add_action( 'woocommerce_add_to_cart', [ $this, 'set_cart_timestamp' ] );
        add_action( 'woocommerce_cart_loaded_from_session', [ $this, 'check_cart_expiration' ] );
        add_action( 'woocommerce_before_cart', [ $this, 'check_cart_expiration' ] );
        add_action( 'woocommerce_before_checkout_form', [ $this, 'check_cart_expiration' ] );
        // Add Cart Notice
        add_action( 'wp', [$this, 'add_cart_reserved_notices'] );
        // Load Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts(){
        if( is_cart() ){
            wp_enqueue_style('woolentor-cart-reserve', MODULE_ASSETS . '/css/frontend.css', [], WOOLENTOR_VERSION);
            wp_enqueue_script('woolentor-cart-reserve', MODULE_ASSETS . '/js/frontend.js', ['jquery'], WOOLENTOR_VERSION, true);
        }
    }

    /**
     * Set cart timestamp when items added
     */
    public function set_cart_timestamp(){
        WC()->session->set( 'woolentor_cart_reserve_time', time() );
    }

    // Cart Reserved Notices
    public function add_cart_reserved_notices(){
        if ( wc_get_page_id( 'cart' ) == get_the_ID() ) {
            // Add Cart Notice
            if ( wc_post_content_has_shortcode( 'woocommerce_cart' ) || !empty( \Woolentor_Template_Manager::instance()->get_template_id( 'productcartpage' ) ) ) {
                add_action( 'woocommerce_before_cart_contents', [ $this, 'add_cart_reserve_notice' ] );
            } else {
                add_filter( 'the_content', [$this, 'the_content_hook'] );
            }
        }
    }

    /**
	 * Cart Reserved Timer Content Hook
	 */
    public function the_content_hook( $content ) {
        return $this->reserve_notice_html( $content );
    }

    /**
	 * Cart Reserved Timer Content Hook
	 */
    public function add_cart_reserve_notice() {
        echo $this->reserve_notice_html();
    }

    /**
     * Add cart reserve notice
     */
    public function reserve_notice_html( $content = '' ){

        if( WC()->cart->is_empty() ){
            return $content;
        }
    
        $cart_time = WC()->session->get( 'woolentor_cart_reserve_time' );
        if( ! $cart_time ){
            $this->set_cart_timestamp();
            $cart_time = time();
        }
    
        $reserve_time = woolentor_get_option( 'reserve_time', 'woolentor_cart_reserve_timer_settings', 60 );

        // Add filter hook for reserve time
        $reserve_time = apply_filters( 'woolentor_cart_reserve_time', $reserve_time, $this->get_cart_product_ids() );
        
        if( !$reserve_time ){
            return $content;
        }

        $expiry_time = $cart_time + ( $reserve_time * 60 );
        $remaining_time = $expiry_time - time();
    
        if( $remaining_time > 0 ){
            // Get settings
            $cart_message = woolentor_get_option( 'cart_message', 'woolentor_cart_reserve_timer_settings', esc_html__('An item of your cart is in high demand.', 'woolentor') );
            $timer_message = woolentor_get_option( 'timer_message', 'woolentor_cart_reserve_timer_settings', esc_html__('Your cart is saved for {time} minutes!', 'woolentor') );
            $notice_icon = woolentor_get_option( 'notice_icon', 'woolentor_cart_reserve_timer_settings', 'fire' );
    
            $notice_html = '<div class="woolentor-cart-reserve-notice">';
            $notice_html .= '<p>'. $this->get_icon_html($notice_icon) . ' ' . esc_html($cart_message) . '</p>';
            $notice_html .= '<p>' . str_replace('{time}', '<span class="woolentor-timer" data-expires="' . esc_attr( $expiry_time ) . '"></span>', esc_html($timer_message)) . '</p>';
            $notice_html .= '</div>';

            // Add filter hook for notice HTML
            return apply_filters( 'woolentor_cart_reserve_notice_html', $notice_html.$content, array(
                'cart_message' => $cart_message,
                'timer_message' => $timer_message,
                'notice_icon' => $notice_icon,
                'expiry_time' => $expiry_time
            ) );
            
        }else{
            return $content;
        }

    }

    /**
     * Get cart product IDs
     */
    private function get_cart_product_ids(){
        $product_ids = [];
        foreach( WC()->cart->get_cart() as $cart_item ){
            $product_ids[] = $cart_item['product_id'];
        }
        return $product_ids;
    }
    

    /**
     * Check cart expiration
     */
    public function check_cart_expiration(){
        if( ! WC()->cart->is_empty() && is_cart()){
            $cart_time = WC()->session->get( 'woolentor_cart_reserve_time' );
            if( ! $cart_time ){
                $this->set_cart_timestamp();
                return;
            }
    
            $reserve_time = woolentor_get_option( 'reserve_time', 'woolentor_cart_reserve_timer_settings', 60 );
            $reserve_time = apply_filters( 'woolentor_cart_reserve_time', $reserve_time, $this->get_cart_product_ids() );

            if( !$reserve_time ){
                return;
            }

            $expiry_time = $cart_time + ( $reserve_time * 60 );
    
            if( time() > $expiry_time ){
                $expire_action = woolentor_get_option( 'expire_action', 'woolentor_cart_reserve_timer_settings', 'hide' );

                // Add action hook for expiration
                do_action( 'woolentor_cart_reserve_expired' );
                
                if($expire_action === 'clear'){
                    WC()->cart->empty_cart();
                    wc_add_notice( __( 'Your cart has expired and items have been removed.', 'woolentor' ), 'notice' );
                }
            }
        }
    }

    /**
     * Get icon HTML
     */
    private function get_icon_html($icon){
        $icons = [
            'none' => '',
            'fire' => '🔥',
            'hourglass' => '⌛',
            'bell' => '🔔',
            'watch' => '⏱️',
            'timer' => '⏳',
            'rocket' => '🚀',
            'alert' => '🚨',
            'spark' => '✨'
        ];

        return isset($icons[$icon]) ? $icons[$icon] : $icons['fire'];
    }

}