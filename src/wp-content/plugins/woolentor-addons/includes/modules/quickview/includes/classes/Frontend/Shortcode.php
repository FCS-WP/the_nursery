<?php
namespace Woolentor\Modules\QuickView\Frontend;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Shortcode handler class
 */
class Shortcode {
    use Singleton;

    /**
     * [__construct] Class construct
     */
    function __construct() {
        add_shortcode( 'woolentor_quickview_button', [ $this, 'button_shortcode' ] );
    }

    /**
     * [button_shortcode] Button Shortcode callable function
     * @param  [type] $atts 
     * @param  string $content
     * @return [HTML] 
     */
    public function button_shortcode( $atts, $content = '' ){

        global $product;
        $product_id = $product_url = '';
        if ( $product && is_a( $product, 'WC_Product' ) ) {
            $product_id  = $product->get_id();
            $product_url = $product->get_permalink();
        }

        /**
         * Get Settings data
         */
        $button_text      = woolentor_get_option( 'button_text', 'woolentor_quickview_settings', 'QuickView' );
        $button_position  = woolentor_get_option( 'button_position', 'woolentor_quickview_settings', 'before_cart_btn' );
        $icon_type        = woolentor_get_option( 'button_icon_type', 'woolentor_quickview_settings', 'default' );
        $icon_position    = woolentor_get_option( 'button_icon_position', 'woolentor_quickview_settings', 'before_text' );

        $button_icon  = $this->get_icon();
        if( !empty( $button_text ) ){
            $button_text = '<span class="woolentor-quickview-btn-text">'.wp_kses_post($button_text).'</span>';
        }

        $button_class = [
            'woolentor-quickview-btn',
            'woolentor-quickview-btn-pos-'.$button_position,
            'woolentor-quickview-btn-icon-'.$icon_type,
            'woolentor-quickview-icon-pos-'.$icon_position,
        ];

        // Shortcode atts
        $default_atts = [
            'product_id'     => $product_id,
            'button_url'     => $product_url,
            'button_class'   => implode(' ', $button_class ),
            'button_text'    => $button_icon.$button_text,
            'template_name'  => 'button',
        ];
        $atts = shortcode_atts( $default_atts, $atts, $content );
        return Button_Manager::instance()->button_html( $atts );

    }

    /**
     * [get_icon]
     * @param  string $type
     * @return [HTML]
     */
    public function get_icon(){

        $default_icon = woolentor_quickview_icon_list('default');
        
        $button_text        = woolentor_get_option( 'button_text', 'woolentor_quickview_settings', 'QuickView' );
        $button_icon_type   = woolentor_get_option( 'button_icon_type', 'woolentor_quickview_settings', 'default' );

        if( $button_icon_type === 'customicon' ){
            $button_icon = woolentor_get_option( 'button_icon','woolentor_quickview_settings', 'sli sli-eye' );
            return '<span class="woolentor-quickview-btn-icon"><i class="'.esc_attr( $button_icon ).'"></i></span>';
        }elseif( $button_icon_type === 'customimage' ){
            $button_image = woolentor_get_option( 'button_custom_image','woolentor_quickview_settings', '' );
            return '<span class="woolentor-quickview-btn-image"><img src="'.esc_url( $button_image ).'" alt="'.esc_attr( $button_text ).'" /></span>';
        }elseif( $button_icon_type === 'default' ){
            return $default_icon;
        }else{
            $button_icon = '';
        }

        return $button_icon;

    }


}