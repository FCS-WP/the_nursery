<?php
namespace Woolentor\Modules\Smart_Cross_Sell_Popup\Admin;
use WooLentor\Traits\Singleton;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Fields {
    use Singleton;

    public function __construct(){
        add_filter( 'woolentor_admin_fields_vue', [ $this, 'admin_fields' ], 99, 1 );
    }

    /**
     * Admin Field Register
     * @param mixed $fields
     * @return mixed
     */
    public function admin_fields( $fields ){
        
        if( woolentor_is_pro() && method_exists( '\WoolentorPro\Modules\Smart_Cross_Sell_Popup\Admin\Fields', 'sitting_fields') ){
            array_splice( $fields['woolentor_others_tabs'], 17, 0, \WoolentorPro\Modules\Smart_Cross_Sell_Popup\Admin\Fields::instance()->sitting_fields() );
        }else{
            array_splice( $fields['woolentor_others_tabs'], 17, 0, $this->sitting_fields() );
        }

        return $fields;
    }

    public function sitting_fields(){
        $fields = [
            array(
                'id'     => 'woolentor_smart_cross_sell_popup_settings',
                'name'    => esc_html__( 'Smart Cross-sell Popup', 'woolentor' ),
                'type'     => 'module',
                'default'  => 'off',
                'section'  => 'woolentor_smart_cross_sell_popup_settings',
                'option_id'=> 'enable',
                'require_settings' => true,
                'documentation' => esc_url('https://woolentor.com/doc/smart-cross-sell-popup-module-in-woocommerce/'),
                'setting_fields' => array(
                    
                    array(
                        'id'  => 'enable',
                        'name' => esc_html__( 'Enable / Disable', 'woolentor' ),
                        'desc'  => esc_html__( 'Enable/Disable Smart Cross-sell Popup module.', 'woolentor' ),
                        'type'  => 'checkbox',
                        'default' => 'off',
                        'class' => 'woolentor-action-field-left'
                    ),
    
                    // General Settings
                    array(
                        'id'      => 'general_settings_heading',
                        'type'      => 'title',
                        'heading'  => esc_html__( 'General Settings', 'woolentor' ),
                        'size'      => 'woolentor_style_seperator',
                    ),
    
                    array(
                        'id'        => 'popup_title',
                        'name'       => esc_html__( 'Popup Title', 'woolentor' ),
                        'desc'        => esc_html__( 'Enter the title for the popup.', 'woolentor' ),
                        'type'        => 'text',
                        'default'     => esc_html__( 'You May Also Like', 'woolentor' ),
                        'class'       => 'woolentor-action-field-left',
                    ),
    
                    array(
                        'id'    => 'product_limit',
                        'name'   => esc_html__( 'Product Limit', 'woolentor' ),
                        'desc'    => esc_html__( 'Set maximum number of products to display.', 'woolentor' ),
                        'type'    => 'number',
                        'default' => '4',
                        'min'     => 1,
                        'max'     => 4,
                        'class'   => 'woolentor-action-field-left',
                    ),
    
                    array(
                        'id'    => 'trigger_typep',
                        'name'   => esc_html__( 'Trigger Type', 'woolentor' ),
                        'desc'    => esc_html__( 'Select when to show the popup.', 'woolentor' ),
                        'type'    => 'select',
                        'default' => 'add_to_cart',
                        'options' => array(
                            'add_to_cart' => esc_html__('After Add to Cart','woolentor'),
                        ),
                        'is_pro' => true,
                        'class'   => 'woolentor-action-field-left'
                    ),

                    array(
                        'id'    => 'product_sourcep',
                        'name'   => esc_html__( 'Product Source', 'woolentor' ),
                        'type'    => 'select',
                        'default' => 'cross_sells',
                        'options' => array(
                            'cross_sells' => esc_html__('Cross-sells', 'woolentor'),
                        ),
                        'class'       => 'woolentor-action-field-left',
                        'is_pro' => true,
                    ),
    
                    // Style Settings
                    array(
                        'id'      => 'style_settings_heading',
                        'type'      => 'title',
                        'heading'  => esc_html__( 'Style Settings', 'woolentor' ),
                        'size'      => 'woolentor_style_seperator',
                    ),
    
                    array(
                        'id'    => 'popup_width',
                        'name'   => esc_html__( 'Popup Width', 'woolentor' ),
                        'desc'    => esc_html__( 'Set popup width in pixel.', 'woolentor' ),
                        'type'    => 'text',
                        'default' => '700px',
                        'class'   => 'woolentor-action-field-left'
                    ),
    
                    array(
                        'id'    => 'button_color',
                        'name'   => esc_html__( 'Button Color', 'woolentor' ),
                        'desc'    => esc_html__( 'Set button color.', 'woolentor' ),
                        'type'    => 'color',
                        'default' => '#ffffff',
                        'class'   => 'woolentor-action-field-left'
                    ),
    
                    array(
                        'id'    => 'button_hover_color',
                        'name'   => esc_html__( 'Button Hover Color', 'woolentor' ),
                        'desc'    => esc_html__( 'Set button hover color.', 'woolentor' ),
                        'type'    => 'color',
                        'default' => '#ffffff',
                        'class'   => 'woolentor-action-field-left'
                    ),
    
                )
            )
        ];

        return $fields;
    }


}