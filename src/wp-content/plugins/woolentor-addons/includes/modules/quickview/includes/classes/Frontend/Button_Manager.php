<?php
namespace Woolentor\Modules\QuickView\Frontend;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Manage Button class
 */
class Button_Manager {
    use Singleton;
    
    /**
     * Class construct
     */
    private function __construct() {
        add_action( 'init', [ $this, 'button_manager' ] );
    }

    /**
     * [button_manager] Button Manager
     * @return [void]
     */
    public function button_manager(){

        $button_position  = woolentor_get_option( 'button_position', 'woolentor_quickview_settings', 'before_cart_btn' );        
        
        // Shop Button Position
        if( $button_position !== 'use_shortcode' && $this->is_enable() == 'on' ){
            switch ( $button_position ) {
                case 'before_cart_btn':
                    add_action( 'woocommerce_after_shop_loop_item', [ $this, 'button_print' ], 7 );
                    break;

                case 'top_thumbnail':
                    add_action( 'woocommerce_before_shop_loop_item', [ $this, 'button_print' ], 5 );
                    break;
                
                default:
                    add_action( 'woocommerce_after_shop_loop_item', [ $this, 'button_print' ], 20 );
                    break;
            }
        }


    }

    /**
     * [button_print]
     * @return [void]
     */
    public function button_print(){
        echo woolentor_do_shortcode( 'woolentor_quickview_button' );
    }

    /**
     * [button_html] Button HTML
     * @param  [type] $atts template attr
     * @return [HTML]
     */
    public function button_html( $atts ) {
        $button_attr = apply_filters( 'woolentor_quickview_button_arg', $atts );
        return woolentor_get_template( 'quickview-'.$atts['template_name'], $button_attr, false, \Woolentor\Modules\QuickView\TEMPLATE_PATH );
    }

    /**
     * [is_enable] Check quickview enable
     * @return [bool]
     */
    public function is_enable(){

        $enable_button           = woolentor_get_option( 'enable_on_shop_archive', 'woolentor_quickview_settings', 'off' ) === 'on';
        $button_enable_on_mobile = woolentor_get_option( 'enable_on_mobile', 'woolentor_quickview_settings', 'off' ) === 'on';

        if( !wp_is_mobile() && $enable_button ){
            $show_button = 'on';
        }else if( wp_is_mobile() && $button_enable_on_mobile ){
           $show_button = 'on';
        }else{
            $show_button = 'off';
        }

        return $show_button;

    }


}