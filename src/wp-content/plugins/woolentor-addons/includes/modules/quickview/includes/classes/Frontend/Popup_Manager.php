<?php
namespace Woolentor\Modules\QuickView\Frontend;
use WooLentor\Traits\Singleton;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Popup_Manager {
    use Singleton;

    public function __construct(){
        add_action( 'woolentor_footer_render_content', [ $this, 'quick_view_html' ], 10 );

        // Popup content
        $this->popup_content();

    }

    // HTML Wrapper for QuickView
    public function quick_view_html(){
        woolentor_get_template( 'quickview-modal-wrap', null, true, \Woolentor\Modules\QuickView\TEMPLATE_PATH );
    }

    /**
     * [is_fire_quickview_ajax] If our ajax action is fire
     * @return boolean
     */
    public function is_fire_quickview_ajax() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'woolentor_quickview' ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * [remove_form_action_url] Remove single product page redirect. 
     * @param  [URL] $value
     * @return [URL] 
     */
    public function remove_form_action_url( $value ) {
        if ( $this->is_fire_quickview_ajax() ) {
            return '';
        }
        return $value;
    }

    /**
     * [popup_action_content] Popup Content
     * @return [void]
     */
    public function popup_content() {

        // Image.
        $this->image_manager();

        // Content.
        $this->popup_content_manager();

        // Social share button
        if( woolentor_get_option( 'enable_social_share','woolentor_quickview_settings','on' ) === 'on' ){
            if(  woolentor_get_option( 'social_share_display_from','woolentor_quickview_settings','custom' ) === 'custom' ){
                add_action( 'woolentor_quickview_content', [ $this, 'social_share' ], 50 );
            }else{
                add_action( 'woolentor_quickview_content', 'woocommerce_template_single_sharing', 45 );
            }
        }


    }

    /**
     * [popup_content_manager]
     * @return [void]
     */
    public function popup_content_manager(){

        $default_content = [
            'title'         => esc_html__( 'Title', 'woolentor' ),
            'rating'        => esc_html__( 'Rating', 'woolentor' ),
            'price'         => esc_html__( 'Price', 'woolentor' ),
            'excerpt'       => esc_html__( 'Excerpt', 'woolentor' ),
            'add_to_cart'   => esc_html__( 'Add to cart', 'woolentor' ),
            'meta'          => esc_html__( 'Meta', 'woolentor' ),
        ];
        $element_list = woolentor_get_option( 'content_to_show','woolentor_quickview_settings', $default_content );
        $element_list = is_array($element_list) ? $element_list : [];
        $priority = 0;
        foreach ( $element_list as $elementkey => $element ) {
            $priority += 5;
            $this->popup_display_content( $elementkey, $priority );
        }

    }

    /**
     * [popup_display_content]
     * @param  [string] $element Element content key
     * @return [void] 
     */
    public function popup_display_content( $element, $priority ){

        switch ( $element ) {

            case 'rating':
                add_action( 'woolentor_quickview_content', 'woocommerce_template_single_rating', $priority );
                break;

            case 'price':
                add_action( 'woolentor_quickview_content', 'woocommerce_template_single_price', $priority );
                break;

            case 'excerpt':
                add_action( 'woolentor_quickview_content', 'woocommerce_template_single_excerpt', $priority );
                break;

            case 'add_to_cart':
                add_action( 'woolentor_quickview_content', 'woocommerce_template_single_add_to_cart', $priority );
                break;

            case 'meta':
                add_action( 'woolentor_quickview_content', 'woocommerce_template_single_meta', $priority );
                break;
            
            default:
                case 'title':
                add_action( 'woolentor_quickview_content', 'woocommerce_template_single_title', $priority );
                break;

        }


    }

    /**
     * [image_manager]
     * @return [void]
     */
    public function image_manager(){
        $image_layout = woolentor_get_option( 'thumbnail_layout','woolentor_quickview_settings', 'slider' );

        if( $image_layout === 'theme' ){
            add_action( 'woolentor_quickview_image', 'woocommerce_show_product_sale_flash', 10 );
            add_action( 'woolentor_quickview_image', 'woocommerce_show_product_images', 20 );
        }else{
            $atts = [
                'thumbnail_layout' => woolentor_get_option( 'thumbnail_layout', 'woolentor_quickview_settings', 'slider' )
            ];
            $image_attr = apply_filters( 'woolentor_quickview_image_arg', $atts );
            add_action( 'woolentor_quickview_image', 'woocommerce_show_product_sale_flash', 10 );
            add_action( 'woolentor_quickview_image', function() use ( $image_attr ){
                woolentor_get_template( 'quickview-product-images', $image_attr, true, \Woolentor\Modules\QuickView\TEMPLATE_PATH );
            }, 20 );
        }

    }

    /**
     * [social_media_share]
     * @return [void]
     */
    public function social_share(){
        $atts = [];
        $social_share_attr = apply_filters( 'woolentor_quickview_social_share_arg', $atts );
        woolentor_get_template( 'quickview-social-share', $social_share_attr, true, \Woolentor\Modules\QuickView\TEMPLATE_PATH );
    }
    

}