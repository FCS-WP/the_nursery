<?php
namespace Woolentor\Modules\QuickView\Admin;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Fields {

    use Singleton;
    public function __construct(){
        add_filter( 'woolentor_admin_fields_vue', [ $this, 'admin_fields' ], 99, 1 );
    }

    public function admin_fields( $fields ){

        array_splice( $fields['woolentor_others_tabs'], 10, 0, $this->quickview_sitting_fields() );
        

        if( woolentor_is_pro() && \Woolentor\Modules\QuickView\ENABLED ){
            $fields['woolentor_elements_tabs'][] = [
                'id'  => 'wl_quickview_product_image',
                'name' => esc_html__( 'Quick view .. image', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on'
            ];

            // Block
            $fields['woolentor_gutenberg_tabs'][] = [
                'id'  => 'quickview_product_image',
                'name' => esc_html__( 'Quick view .. image', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on',
            ];

        }

        return $fields;
    }

    /**
     * Currency Fields;
     */
    public function quickview_sitting_fields(){
        $fields = [
            [
                'id'   => 'woolentor_quickview_settings',
                'name'  => esc_html__( 'Quick View', 'woolentor-pro' ),
                'type'   => 'module',
                'default'=> 'off',
                'section'  => 'woolentor_quickview_settings',
                'option_id' => 'enable',
                'documentation' => esc_url('https://woolentor.com/doc/quick-view/'),
                'require_settings'  => true,
                'setting_fields' => [
                    
                    [
                        'id'    => 'enable',
                        'name'   => esc_html__( 'Enable / Disable', 'woolentor' ),
                        'desc'    => esc_html__( 'Enable / disable this module.', 'woolentor' ),
                        'type'    => 'checkbox',
                        'default' => 'on',
                        'class'   => 'woolentor-action-field-left'
                    ],

                    [
                        'id'    => 'enable_on_shop_archive',
                        'name'   => esc_html__( 'Enable quick view in Shop / Archive page', 'woolentor' ),
                        'desc'    => esc_html__( 'Enable this option to display a quick view on shop and archive page.', 'woolentor' ),
                        'type'    => 'checkbox',
                        'default' => 'off',
                        'class'   => 'woolentor-action-field-left'
                    ],
                    [
                        'id'    => 'enable_on_mobile',
                        'name'   => esc_html__( 'Enable quick view on mobile', 'woolentor' ),
                        'desc'    => esc_html__( 'Enable this option to display a quick view on mobile devices.', 'woolentor' ),
                        'type'    => 'checkbox',
                        'default' => 'off',
                        'class'   => 'woolentor-action-field-left',
                        'condition' => [
                            'key' => 'enable_on_shop_archive',
                            'operator' => '==',
                            'value' => 'on'
                        ],
                    ],

                    [
                        'id'      => 'button_heading',
                        'heading'  => esc_html__( 'Button Settings', 'woolentor' ),
                        'type'      => 'title',
                        'condition' => [
                            'key' => 'enable_on_shop_archive',
                            'operator' => '==',
                            'value' => 'on'
                        ],
                    ],

                    [
                        'id'    => 'button_position',
                        'name'   => esc_html__( 'Button position', 'woolentor' ),
                        'type'    => 'select',
                        'default' => 'before_cart_btn',
                        'options' => [
                            'before_cart_btn' => esc_html__( 'Before Add To Cart', 'woolentor' ),
                            'after_cart_btn'  => esc_html__( 'After Add To Cart', 'woolentor' ),
                            'top_thumbnail'   => esc_html__( 'Top On Image', 'woolentor' ),
                            'use_shortcode'   => esc_html__( 'Use Shortcode', 'woolentor' ),
                        ],
                        'class'   => 'woolentor-action-field-left',
                        'condition' => [
                            'key' => 'enable_on_shop_archive',
                            'operator' => '==',
                            'value' => 'on'
                        ],
                    ],

                    [
                        'id'        => 'shortcode_info_data',
                        'html'      => wp_kses_post('Place this shortcode <code>[woolentor_quickview_button]</code> wherever you want the quick view button to appear.'),
                        'type'      => 'html',
                        'condition' => [ 'key' => 'button_position|enable_on_shop_archive', 'operator' => '==|==', 'value' => 'use_shortcode|on' ],
                        'class'     => 'woolentor_option_field_notice'
                    ],

                    [
                        'id'        => 'button_text',
                        'name'       => esc_html__( 'Button text', 'woolentor' ),
                        'desc'        => esc_html__( 'Enter your quick view button text.', 'woolentor' ),
                        'type'        => 'text',
                        'default'     => esc_html__( 'Quick view', 'woolentor' ),
                        'placeholder' => esc_html__( 'Quick view', 'woolentor' ),
                        'class'       => 'woolentor-action-field-left',
                        'condition' => [
                            'key' => 'enable_on_shop_archive',
                            'operator' => '==',
                            'value' => 'on'
                        ],
                    ],
                    [
                        'id'    => 'button_icon_type',
                        'name'   => esc_html__( 'Button icon type', 'woolentor' ),
                        'desc'    => esc_html__( 'Choose an icon type for the quick view button from here.', 'woolentor' ),
                        'type'    => 'select',
                        'default' => 'default',
                        'options' => [
                            'none'     => esc_html__( 'None', 'woolentor' ),
                            'default'  => esc_html__( 'Default', 'woolentor' ),
                            'customicon' => esc_html__( 'Custom Icon', 'woolentor' ),
                            'customimage'=> esc_html__( 'Custom Image', 'woolentor' ),
                        ],
                        'class'       => 'woolentor-action-field-left',
                        'condition' => [
                            'key' => 'enable_on_shop_archive',
                            'operator' => '==',
                            'value' => 'on'
                        ],
                    ],
                    [
                        'id'    => 'button_icon',
                        'name'   => esc_html__( 'Button Icon', 'woolentor-pro' ),
                        'desc'    => esc_html__( 'You can manage the button icon.', 'woolentor' ),
                        'type'    => 'iconpicker',
                        'default' => 'sli sli-eye',
                        'class'   => 'woolentor_icon_picker woolentor-action-field-left',
                        'condition'   => [ 'key' => 'button_icon_type|enable_on_shop_archive', 'operator' => '==|==', 'value' => 'customicon|on' ],
                    ],
                    [
                        'id'    => 'button_custom_image',
                        'name'   => esc_html__( 'Button custom icon', 'woolentor' ),
                        'desc'    => esc_html__( 'Upload you custom icon from here.', 'woolentor' ),
                        'type'    => 'imageupload',
                        'options' => [
                            'button_label'        => esc_html__( 'Upload', 'woolentor' ),   
                            'button_remove_label' => esc_html__( 'Remove', 'woolentor' ),   
                        ],
                        'class' => 'woolentor-action-field-left',
                        'condition'   => [ 'key' => 'button_icon_type|enable_on_shop_archive', 'operator' => '==|==', 'value' => 'customimage|on' ],
                    ],
                    [
                        'id'    => 'button_icon_position',
                        'name'   => esc_html__( 'Button icon Position', 'woolentor' ),
                        'desc'    => esc_html__( 'Choose an icon type for the quick view button from here.', 'woolentor' ),
                        'type'    => 'select',
                        'default' => 'before_text',
                        'options' => [
                            'before_text' => esc_html__( 'Before Text', 'woolentor' ),
                            'after_text'  => esc_html__( 'After Text', 'woolentor' ),
                        ],
                        'class'       => 'woolentor-action-field-left',
                        'condition'   => [ 'key' => 'button_icon_type|enable_on_shop_archive', 'operator' => '!=|==', 'value' => 'none|on' ],
                    ],

                    [
                        'id'      => 'modal_box_heading',
                        'heading'  => esc_html__( 'Popup Settings', 'woolentor' ),
                        'type'      => 'title'
                    ],
                    [
                        'id' => 'content_to_show',
                        'name' => esc_html__('Select content to show', 'woolentor'),
                        'desc'    => esc_html__( 'Choose which content should be presented on the popup window.', 'woolentor' ),
                        'type' => 'shortable',
                        'options' => [
                            'title'         => esc_html__( 'Title', 'woolentor' ),
                            'rating'        => esc_html__( 'Rating', 'woolentor' ),
                            'price'         => esc_html__( 'Price', 'woolentor' ),
                            'excerpt'       => esc_html__( 'Excerpt', 'woolentor' ),
                            'add_to_cart'   => esc_html__( 'Add to cart', 'woolentor' ),
                            'meta'          => esc_html__( 'Meta', 'woolentor' ),
                        ],
                        'default' => [
                            'title'   		=> esc_html__( 'Title', 'woolentor' ),
                            'rating'    	=> esc_html__( 'Rating', 'woolentor' ),
                            'price'  		=> esc_html__( 'Price', 'woolentor' ),
                            'excerpt'   	=> esc_html__( 'Excerpt', 'woolentor' ),
                            'add_to_cart'   => esc_html__( 'Add to cart', 'woolentor' ),
                            'meta'   		=> esc_html__( 'Meta', 'woolentor' ),
                        ],
                    ],

                    [
                        'id'  => 'enable_ajax_cart',
                        'name'  => esc_html__( 'Enable AJAX add to cart', 'woolentor' ),
                        'type'  => 'checkbox',
                        'default' => 'on',
                        'desc'    => esc_html__( 'Enable this to activate AJAX add to cart feature in the popup window.', 'woolentor' ),
                        'class' => 'woolentor-action-field-left'
                    ],

                    [
                        'id'    => 'thumbnail_layout',
                        'name'   => esc_html__( 'Thumbnail layout', 'woolentor' ),
                        'desc'    => esc_html__( 'Choose a thumbnail layout from here.', 'woolentor' ),
                        'type'    => 'select',
                        'default' => 'slider',
                        'options' => [
                            'slider'	 => esc_html__( 'Slider', 'woolentor' ),
                            'singleimage'=> esc_html__( 'Single Image', 'woolentor' ),
                            'theme' 	 => esc_html__( 'Theme', 'woolentor' ),
                        ],
                        'class' => 'woolentor-action-field-left',
                    ],

                    [
                        'id'   => 'enable_social_share',
                        'name'  => esc_html__( 'Enable social share button', 'woolentor' ),
                        'type'   => 'checkbox',
                        'default'=> 'on',
                        'desc'   => esc_html__( 'Enable social share button.', 'woolentor' ),
                        'class'  => 'woolentor-action-field-left'
                    ],
    
                    [
                        'id'    => 'social_share_display_from',
                        'name'   => esc_html__( 'Social share button display from', 'woolentor' ),
                        'desc'    => esc_html__( 'If you choose default this button comes from ShopLentor otherwise display from your theme WooCommerce hook.', 'woolentor' ),
                        'type'    => 'select',
                        'default' => 'custom',
                        'options' => [
                            'custom' => esc_html__( 'Custom', 'woolentor' ),
                            'theme'  => esc_html__( 'Theme', 'woolentor' ),
                        ],
                        'class'     => 'woolentor-action-field-left',
                        'condition' => [ 'key'=>'enable_social_share', 'operator'=>'==', 'value'=>'on' ],
                    ],
    
                    [
                        'id' => 'social_share_buttons',
                        'name' => esc_html__('Enable share buttons', 'woolentor'),
                        'desc'    => esc_html__( 'You can manage your social share buttons.', 'woolentor' ),
                        'type' => 'shortable',
                        'options' => [
                            'facebook'      => esc_html__( 'Facebook', 'woolentor' ),
                            'twitter'       => esc_html__( 'Twitter', 'woolentor' ),
                            'pinterest'     => esc_html__( 'Pinterest', 'woolentor' ),
                            'linkedin'      => esc_html__( 'Linkedin', 'woolentor' ),
                            'email'      	=> esc_html__( 'Email', 'woolentor' ),
                            'reddit'   		=> esc_html__( 'Reddit', 'woolentor' ),
                            'telegram'   	=> esc_html__( 'Telegram', 'woolentor' ),
                            'odnoklassniki' => esc_html__( 'Odnoklassniki', 'woolentor' ),
                            'whatsapp'   	=> esc_html__( 'WhatsApp', 'woolentor' ),
                            'vk'   			=> esc_html__( 'VK', 'woolentor' ),
                        ],
                        'default' => [
                            'facebook'   => esc_html__( 'Facebook', 'woolentor' ),
                            'twitter'    => esc_html__( 'Twitter', 'woolentor' ),
                            'pinterest'  => esc_html__( 'Pinterest', 'woolentor' ),
                            'linkedin'   => esc_html__( 'Linkedin', 'woolentor' ),
                            'telegram'   => esc_html__( 'Telegram', 'woolentor' ),
                        ],
                        'condition' => [ 'key'=>'enable_social_share|social_share_display_from', 'operator'=>'==|==', 'value'=>'on|custom' ],
                    ],

                    [
                        'id'        => 'social_share_button_title',
                        'name'       => esc_html__( 'Social share button title', 'woolentor' ),
                        'desc'        => esc_html__( 'Enter your social share button title.', 'woolentor' ),
                        'type'        => 'text',
                        'default'     => esc_html__( 'Share:', 'woolentor' ),
                        'placeholder' => esc_html__( 'Share', 'woolentor' ),
                        'class'       => 'woolentor-action-field-left',
                        'condition' => [ 'key'=>'enable_social_share|social_share_display_from', 'operator'=>'==|==', 'value'=>'on|custom' ],
                    ]
                    

                ]
            ]
        ];

        return $fields;

    }

}