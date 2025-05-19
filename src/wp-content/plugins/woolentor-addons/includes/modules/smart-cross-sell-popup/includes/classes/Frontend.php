<?php
namespace Woolentor\Modules\Smart_Cross_Sell_Popup;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Frontend {

    private static $_instance = null;
    private $popup_shown = false;

    /**
     * Get Instance
     */
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){
        // Check WooCommerce
        if ( ! function_exists( 'WC' ) ) {
            return;
        }
        $this->init();
    }

    /**
     * Initialize
     */
    public function init(){
        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // AJAX handlers for getting cross sell products
        add_action( 'wp_ajax_woolentor_get_cross_sell_product', [ $this, 'get_cross_sell_products' ] );
        add_action( 'wp_ajax_nopriv_woolentor_get_cross_sell_product', [ $this, 'get_cross_sell_products' ] );

        // Add popup HTML
        add_action( 'wp_footer', [ $this, 'popup_markup' ] );

        // AJAX handlers for add to cart
        add_action( 'wp_ajax_woolentor_cross_sell_add_to_cart', [ $this, 'ajax_add_to_cart' ] );
        add_action( 'wp_ajax_nopriv_woolentor_cross_sell_add_to_cart', [ $this, 'ajax_add_to_cart' ] );

        // Trigger handlers
        add_action( 'woocommerce_add_to_cart', [ $this, 'trigger_on_add_to_cart' ], 20, 6 );

        // Clean up session data
        add_action( 'wp_logout', [ $this, 'cleanup_session' ] );
        add_action( 'woocommerce_cleanup_sessions', [ $this, 'cleanup_session' ] );
    }

    /**
     * Enqueue Scripts
     */
    public function enqueue_scripts(){
        // Only enqueue on necessary pages
        if( !is_product() && !is_shop() && !is_archive() && !is_cart() && !is_checkout()) {
            return;
        }

        // Add version for cache busting
        $version = defined('WP_DEBUG') && WP_DEBUG ? time() : WOOLENTOR_VERSION;

        wp_enqueue_style(
            'woolentor-smart-cross-sell-popup',
            MODULE_ASSETS . '/css/frontend.css',
            [],
            $version
        );

        wp_enqueue_script(
            'woolentor-smart-cross-sell-popup',
            MODULE_ASSETS . '/js/frontend.js',
            ['jquery'],
            $version,
            true
        );

        $settings = woolentor_smart_cross_sell_get_settings();
        // Localized data
        wp_localize_script( 'woolentor-smart-cross-sell-popup', 'WoolentorCrossSell', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'wcajaxurl' => \WC_AJAX::get_endpoint( 'woolentor_cross_sell_products' ),
            'nonce'   => wp_create_nonce( 'woolentor_cross_sell_nonce' ),
            'i18n'    => [
                'addToCart' => esc_html__('Add to Cart', 'woolentor'),
                'added'     => esc_html__('Added', 'woolentor'),
                'error'     => esc_html__('Error occurred', 'woolentor'),
                'loading'   => esc_html__('Loading...', 'woolentor'),
            ],
            'settings' => [
                'autoClose' => woolentor_get_option( 'auto_close', 'woolentor_smart_cross_sell_popup_settings', false ),
                'autoCloseTime' => woolentor_get_option( 'auto_close_time', 'woolentor_smart_cross_sell_popup_settings', 5000 ),
                'popup_width' => $settings['popup_width'],
                'button_color' => $settings['button_color'],
                'button_hover_color' => $settings['button_hover_color'],
            ]
        ]);
    }

    /**
     * Get Cross Sell Products
     */
    public function get_cross_sell_products(){
        try {
            check_ajax_referer( 'woolentor_cross_sell_nonce', 'nonce' );

            $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

            if(!$product_id) {
                if( \WC()->session && \WC()->session->get('woolentor_cross_sell_popup') ){
                    $product_id = (int) \WC()->session->get('woolentor_last_added_product');
                }else{
                    $product_id = 0;
                }
            }

            if(!$product_id) {
                throw new \Exception(__('Invalid product ID', 'woolentor'));
            }
            
            $product_ids = $this->get_cross_sell_product_ids($product_id);
            
            // Check if products exist
            if(empty($product_ids)) {
                throw new \Exception(__('No cross-sell products found', 'woolentor'));
            }

            ob_start();
            $this->popup_content($product_ids, $product_id);
            $html = ob_get_clean();
            $this->cleanup_session_data();

            wp_send_json_success([
                'html' => $html,
                'id' => $product_id
            ]);
            
        } catch(\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clean up session data
     */
    private function cleanup_session_data() {
        if( \WC()->session ) {
            \WC()->session->set('woolentor_cross_sell_popup', false);
            \WC()->session->set('woolentor_last_added_product', null);
        }
    }

    /**
     * Trigger Popup
     */
    public function trigger_popup($product_id) {
        if( \WC()->session ) {
            \WC()->session->set('woolentor_last_added_product', $product_id);
            \WC()->session->set('woolentor_cross_sell_popup', true);
        }
    }

    /**
     * Trigger on Add to Cart
     */
    public function trigger_on_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ){
        $trigger_type = woolentor_get_option( 'trigger_type', 'woolentor_smart_cross_sell_popup_settings', 'add_to_cart' );
        $last_added_product_id = $variation_id ? $variation_id : $product_id;

        if( $trigger_type == 'add_to_cart' && !$this->popup_shown ){
            $this->trigger_popup($last_added_product_id);
            $this->popup_shown = true;
        }else{
            \WC()->session->set('woolentor_last_added_product', $last_added_product_id);
        }

    }

    /**
     * AJAX Add to Cart Handler
     */
    public function ajax_add_to_cart(){
        try {
            check_ajax_referer( 'woolentor_cross_sell_nonce', 'nonce' );

            if( !isset($_POST['product_id']) ){
                throw new \Exception(__('Invalid product', 'woolentor'));
            }

            $product_id = absint($_POST['product_id']);
            $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

            // Validate product
            $product = wc_get_product($product_id);
            if(!$product) {
                throw new \Exception(__('Product not found', 'woolentor'));
            }

            if(!$product->is_in_stock()) {
                throw new \Exception(__('Product is out of stock', 'woolentor'));
            }

            $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
            if(!$passed_validation) {
                throw new \Exception(__('Product validation failed', 'woolentor'));
            }

            if( \WC()->cart->add_to_cart( $product_id, $quantity ) ){
                do_action( 'woocommerce_ajax_added_to_cart', $product_id );

                // Track conversion if analytics is available
                if( class_exists('\Woolentor\Modules\Smart_Cross_Sell_Popup\Frontend\Analytics') ){
                    // \Woolentor\Modules\Smart_Cross_Sell_Popup\Frontend\Analytics::instance()->track_conversion($product_id);
                }
                
                wp_send_json_success([
                    'message' => __('Product added to cart', 'woolentor'),
                    'cart_count' => \WC()->cart->get_cart_contents_count(),
                    'cart_total' => \WC()->cart->get_cart_total()
                ]);
            } else {
                throw new \Exception(__('Failed to add product to cart', 'woolentor'));
            }

        } catch(\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get Cross Sell Product IDs
     */
    private function get_cross_sell_product_ids($product_id) {

        $product = wc_get_product( $product_id );
        if( !$product ){
            return [];
        }

        // Get cross sell IDs and filter out of stock products
        $cross_sell_ids = $product->get_cross_sell_ids();
        $products = array_filter($cross_sell_ids, function($product_id) {
            $product = wc_get_product($product_id);
            return $product && $product->is_in_stock() && $product->is_purchasable();
        });

        return apply_filters( 'woolentor_cross_sell_products', $products, $product_id );
        
    }

    public function popup_markup(){
        if( is_product() || is_shop() || is_archive() || is_cart() || is_checkout()){
            ?>
                <div id="woolentor-cross-sell-popup" class="woolentor-cross-sell-popup">
                    <div class="woolentor-popup-wrapper"></div>
                </div>
            <?php
            $this->handle_single_product_add_to_cart();
            $this->request_for_popup();
        }
    }

    /**
     * Handle Single Product Add to Cart
     */
    public function handle_single_product_add_to_cart(){
        if( !is_product() ){
            return;
        }

        $trigger_type = woolentor_get_option( 'trigger_type', 'woolentor_smart_cross_sell_popup_settings', 'add_to_cart' );

        // Check if product was just added to cart
        if( isset( $_POST['add-to-cart'] ) && !empty( $_POST['add-to-cart'] ) ){
            $product_id = absint( $_POST['add-to-cart'] );
            \WC()->session->set('woolentor_last_added_product', $product_id);
            if( $trigger_type == 'add_to_cart' ){
                \WC()->session->set('woolentor_cross_sell_popup', true);
            }
        }else{
            \WC()->session->set('woolentor_cross_sell_popup', false);
            \WC()->session->set('woolentor_last_added_product', null);
        }

    }

    public function request_for_popup(){

        if( \WC()->session && \WC()->session->get('woolentor_cross_sell_popup') ){
            $product_id = \WC()->session->get('woolentor_last_added_product') ? (int) \WC()->session->get('woolentor_last_added_product') : 0;
        
            // Check if product was just added to cart
            if( !empty($product_id) ){
                ?>
                <script>
                    ;jQuery(document).ready(function($){
                        $.ajax({
                            url: WoolentorCrossSell.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'woolentor_get_cross_sell_product',
                                product_id: <?php echo $product_id; ?>,
                                nonce: WoolentorCrossSell.nonce
                            },
                            success: function(response){
                                if(response.success && response.data.html.length > 0){
                                    $('.woolentor-popup-wrapper').html(response.data.html);
                                    $('#woolentor-cross-sell-popup').fadeIn();
                                }
                            }
                        });
                    });
                </script>
                <?php
            }

        }
    }


    public function popup_content($products, $product_id = 0){
        if(empty($products)) {
            return;
        }
    
        $last_added_product_title = get_the_title($product_id);
        $product_name = $last_added_product_title ? $last_added_product_title : 'Product';

        $settings = woolentor_smart_cross_sell_get_settings();
        $popup_title = isset($settings['popup_title']) ? $settings['popup_title'] : __('You May Also Like', 'woolentor');
        $columns = isset($settings['columns']) ? absint($settings['columns']) : 3;

        ?>

        <div class="woolentor-popup-header">
            <div class="woolentor-success-message">
                <svg viewBox="0 0 24 24" width="20" height="20"><circle cx="12" cy="12" r="10" fill="#4CAF50"/><path d="M9 12l2 2 4-4" fill="none" stroke="#fff" stroke-width="2"/></svg>
                <span class="added-message"><?php echo esc_html($product_name);?> Has been added</span>
            </div>
            <button class="woolentor-popup-close">&times;</button>
        </div>

        <div class="woolentor-popup-actions">
            <a href="<?php echo wc_get_cart_url(); ?>" class="woolentor-view-cart"><?php echo esc_html__('View Shopping Cart', 'woolentor'); ?></a>
            <button class="woolentor-continue-shopping"><?php echo esc_html__('Continue Shopping', 'woolentor'); ?></button>
        </div>

        <div class="woolentor-popup-recommended">
            <div class="woolentor-cross-sell-popup-content">

                <div class="woolentor-success-message">
                    <h3><?php echo esc_html($popup_title); ?></h3>
                </div>

                <div class="woolentor-cross-sell-products columns-<?php echo esc_attr($columns); ?>">
                    <?php 
                    foreach($products as $product_id): 
                        $product = wc_get_product($product_id);
                    ?>
                        <div class="woolentor-cross-sell-product">
                            <div class="woolentor-product-category">
                                <?php echo wc_get_product_category_list($product_id); ?>
                            </div>
                            <div class="woolentor-product-image">
                                <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                    <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                                </a>
                            </div>
                            <div class="woolentor-product-content">
                                <h4 class="woolentor-product-title">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                </h4>
                                <div class="woolentor-product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                                <button class="woolentor-add-to-cart" data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-quantity="1">
                                    <?php esc_html_e('Add to Cart', 'woolentor'); ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <div class="woolentor-popup-footer">
            <a href="<?php echo wc_get_checkout_url(); ?>" class="woolentor-checkout-button">
                <?php echo esc_html__('Proceed to Checkout', 'woolentor'); ?>
            </a>
        </div>
        
        <?php


    }

    /**
     * Clean up session
     */
    public function cleanup_session() {
        $this->cleanup_session_data();
    }
}