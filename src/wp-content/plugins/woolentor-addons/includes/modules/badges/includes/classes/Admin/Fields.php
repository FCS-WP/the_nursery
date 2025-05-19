<?php
namespace Woolentor\Modules\Badges\Admin;
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
        if( woolentor_is_pro() && method_exists( '\WoolentorPro\Modules\Badges\Product_Badges', 'Fields') ){
            array_splice( $fields['woolentor_others_tabs'], 25, 0, \WoolentorPro\Modules\Badges\Product_Badges::instance()->Fields() );
        }else{
            array_splice( $fields['woolentor_others_tabs'], 13, 0, $this->sitting_fields() );
        }

        return $fields;
    }

    /**
     * Settings Fields;
     */
    public function sitting_fields(){
        $fields = [
            [
                'id'   => 'woolentor_badges_settings',
                'name'  => esc_html__( 'Product Badges', 'woolentor' ),
                'type'   => 'module',
                'default'=> 'off',
                'section'  => 'woolentor_badges_settings',
                'option_id' => 'enable',
                'documentation' => esc_url('https://woolentor.com/doc/product-badges-module/'),
                'require_settings'  => true,
                'setting_fields' => [
                    [
                        'id'    => 'enable',
                        'name'   => esc_html__( 'Enable / Disable', 'woolentor' ),
                        'desc'    => esc_html__( 'Enable / disable this module.', 'woolentor' ),
                        'type'    => 'checkbox',
                        'default' => 'off',
                        'class'   => 'woolentor-action-field-left'
                    ],

                    [
                        'id'        => 'badges_list',
                        'name'       => esc_html__( 'Badge List', 'woolentor' ),
                        'type'        => 'repeater',
                        'title_field' => 'badge_title',
                        'condition'   => [ 'key'=>'enable','operator'=>'==', 'value'=>'on' ],
                        'max_items'   => '2',
                        'message'     => [
                            'title' => esc_html__( 'Upgrade to Premium Version', 'woolentor' ),
                            'desc'  => esc_html__( 'With the free version, you can add 2 badges. To unlock more badges and advanced features, please upgrade to the pro version.', 'woolentor' ),
                            'pro_link' => esc_url('https://woolentor.com/pricing/?utm_source=admin&utm_medium=lockfeatures&utm_campaign=free'),
                        ],
                        'options' => [
                            'button_label' => esc_html__( 'Add New Badge', 'woolentor' ),  
                        ],
                        'fields'  => [
                            [
                                'id'        => 'badge_title',
                                'name'       => esc_html__( 'Badge Title', 'woolentor' ),
                                'type'        => 'text',
                                'class'       => 'woolentor-action-field-left'
                            ],
                            [
                                'id'        => 'badge_type',
                                'name'       => esc_html__( 'Badge Type', 'woolentor' ),
                                'type'        => 'select',
                                'default'     => 'text',
                                'options' => [
                                    'text' => esc_html__( 'Text', 'woolentor-pro' ),
                                    'image'=> esc_html__( 'Image', 'woolentor-pro' ),
                                ],
                                'class'       => 'woolentor-action-field-left'
                            ],
                            [
                                'id'        => 'badge_text',
                                'name'       => esc_html__( 'Badge Text', 'woolentor' ),
                                'type'        => 'text',
                                'class'       => 'woolentor-action-field-left',
                                'condition' => ['key'=> 'badge_type', 'operator'=>'==', 'value'=> 'text' ],
                            ],
                            [
                                'id'  => 'badge_text_color',
                                'name' => esc_html__( 'Text Color', 'woolentor' ),
                                'desc'  => esc_html__( 'Badge text color.', 'woolentor' ),
                                'type'  => 'color',
                                'class' => 'woolentor-action-field-left',
                                'condition' => ['key'=> 'badge_type', 'operator'=>'==', 'value'=> 'text' ],
                            ],
                            [
                                'id'  => 'badge_bg_color',
                                'name' => esc_html__( 'Background Color', 'woolentor' ),
                                'desc'  => esc_html__( 'Badge background color.', 'woolentor' ),
                                'type'  => 'color',
                                'class' => 'woolentor-action-field-left',
                                'condition' => ['key'=> 'badge_type', 'operator'=>'==', 'value'=> 'text' ],
                            ],
                            [
                                'id'              => 'badge_font_size',
                                'name'             => esc_html__( 'Text Font Size (PX)', 'woolentor' ),
                                'desc'              => esc_html__( 'Set the font size for badge text.', 'woolentor' ),
                                'min'               => 1,
                                'max'               => 1000,
                                'default'           => '15',
                                'step'              => '1',
                                'type'              => 'number',
                                'sanitize_callback' => 'number',
                                'condition' => ['key'=> 'badge_type', 'operator'=>'==', 'value'=> 'text' ],
                                'class'       => 'woolentor-action-field-left',
                            ],
                            [
                                'id'    => 'badge_padding',
                                'name'   => esc_html__( 'Badge padding', 'woolentor' ),
                                'desc'    => esc_html__( 'Badge area padding.', 'woolentor' ),
                                'type'    => 'dimensions',
                                'options' => [
                                    'top'   => esc_html__( 'Top', 'woolentor' ),
                                    'right' => esc_html__( 'Right', 'woolentor' ),
                                    'bottom'=> esc_html__( 'Bottom', 'woolentor' ),
                                    'left'  => esc_html__( 'Left', 'woolentor' ),
                                    'unit'  => esc_html__( 'Unit', 'woolentor' ),
                                ],
                                'class' => 'woolentor-action-field-left woolentor-dimention-field-left',
                                'condition' => ['key'=> 'badge_type', 'operator'=>'==', 'value'=> 'text' ],
                            ],
                            [
                                'id'    => 'badge_border_radius',
                                'name'   => esc_html__( 'Badge border radius', 'woolentor' ),
                                'desc'    => esc_html__( 'Badge area button border radius.', 'woolentor' ),
                                'type'    => 'dimensions',
                                'options' => [
                                    'top'   => esc_html__( 'Top', 'woolentor' ),
                                    'right' => esc_html__( 'Right', 'woolentor' ),
                                    'bottom'=> esc_html__( 'Bottom', 'woolentor' ),
                                    'left'  => esc_html__( 'Left', 'woolentor' ),
                                    'unit'  => esc_html__( 'Unit', 'woolentor' ),
                                ],
                                'class' => 'woolentor-action-field-left woolentor-dimention-field-left',
                                'condition' => ['key'=> 'badge_type', 'operator'=>'==', 'value'=> 'text' ],
                            ],
                            [
                                'id'    => 'badge_image',
                                'name'   => esc_html__( 'Badge Image', 'woolentor-pro' ),
                                'desc'    => esc_html__( 'Upload your custom badge from here.', 'woolentor' ),
                                'type'    => 'imageupload',
                                'options' => [
                                    'button_label'        => esc_html__( 'Upload', 'woolentor' ),   
                                    'button_remove_label' => esc_html__( 'Remove', 'woolentor' ),   
                                ],
                                'class' => 'woolentor-action-field-left',
                                'condition'   => [ 'key'=>'badge_type', 'operator'=>'==', 'value'=>'image' ],
                            ],

                            [
                                'id'      => 'badge_setting_heading',
                                'heading'  => esc_html__( 'Badge Settings', 'woolentor' ),
                                'type'      => 'title'
                            ],

                            [
                                'id'    => 'badge_position',
                                'name'   => esc_html__( 'Badge Position', 'woolentor' ),
                                'desc'    => esc_html__( 'Choose a badge position from here.', 'woolentor' ),
                                'type'    => 'select',
                                'default' => 'top_left',
                                'options' => [
                                    'top_left'   => esc_html__( 'Top Left', 'woolentor' ),
                                    'top_right'  => esc_html__( 'Top Right', 'woolentor' ),
                                    'bottom_left'=> esc_html__( 'Bottom Left', 'woolentor' ),
                                    'bottom_right'=> esc_html__( 'Bottom Right', 'woolentor' ),
                                ],
                                'class'       => 'woolentor-action-field-left',
                            ],
                            [
                                'id'    => 'badge_custom_positionp',
                                'name'   => esc_html__( 'Custom Position', 'woolentor' ),
                                'desc'    => esc_html__( 'Badge Custom Position.', 'woolentor' ),
                                'type'    => 'dimensions',
                                'options' => [
                                    'top'   => esc_html__( 'Top', 'woolentor' ),
                                    'right' => esc_html__( 'Right', 'woolentor' ),
                                    'bottom'=> esc_html__( 'Bottom', 'woolentor' ),
                                    'left'  => esc_html__( 'Left', 'woolentor' ),
                                    'unit'  => esc_html__( 'Unit', 'woolentor' ),
                                ],
                                'class' => 'woolentor-action-field-left woolentor-dimention-field-left',
                                'is_pro'    => true,
                            ],
                            [
                                'id'    => 'badge_condition',
                                'name'   => esc_html__( 'Badge Condition', 'woolentor' ),
                                'type'    => 'select',
                                'default' => 'none',
                                'options' => [
                                    'none' => esc_html__( 'Select Option', 'woolentor' ),
                                    'all_product' => esc_html__( 'All Products', 'woolentor' ),
                                    'selected_product'=> esc_html__( 'Selected Product', 'woolentor' ),
                                    'category'=> esc_html__( 'Category', 'woolentor' ),
                                    'on_sale'=> esc_html__( 'On Sale Only', 'woolentor' ),
                                    'outof_stock'=> esc_html__( 'Out Of Stock', 'woolentor' ),
                                ],
                                'class'       => 'woolentor-action-field-left',
                            ],

                            [
                                'id'        => 'categories',
                                'name'       => esc_html__( 'Select Categories', 'woolentor' ),
                                'desc'        => esc_html__( 'Select the categories in which products the badge will be show.', 'woolentor' ),
                                'type'        => 'multiselect',
                                'convertnumber' => true,
                                'options'     => woolentor_taxonomy_list('product_cat','term_id'),
                                'condition'   => [ 'key'=>'badge_condition', 'operator'=>'==', 'value'=>'category' ],
                                'class'       => 'woolentor-action-field-left'
                            ],

                            [
                                'id'        => 'products',
                                'name'       => esc_html__( 'Select Products', 'woolentor' ),
                                'desc'        => esc_html__( 'Select individual products in which the badge will be show.', 'woolentor' ),
                                'type'        => 'multiselect',
                                'convertnumber' => true,
                                'options'     => woolentor_post_name( 'product' ),
                                'condition'   => [ 'key'=>'badge_condition', 'operator'=>'==', 'value'=>'selected_product' ],
                                'class'       => 'woolentor-action-field-left'
                            ],

                            [
                                'id'        => 'exclude_productsp',
                                'name'       => esc_html__( 'Exclude Products', 'woolentor' ),
                                'type'        => 'select',
                                'default'     => 'select_products',
                                'options'     => [
                                    'select_products'  => esc_html__('This is a pro features','woolentor'),
                                ],
                                'condition'   => [ 'key'=>'badge_condition', 'operator'=>'!=', 'value'=>'none' ],
                                'class'       => 'woolentor-action-field-left',
                                'is_pro'      => true,
                            ]


                        ],
                    ],

                ]
            ]
        ];

        return $fields;

    }

}