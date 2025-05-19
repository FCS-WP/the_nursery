<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Recently_Viewed_Products_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-recently-viewed-products';
    }

    public function get_title() {
        return __( 'WL: Recently Viewed Products', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [ 'woolentor-widgets','slick'];
    }

    public function get_script_depends() {
        return ['slick','woolentor-widgets-scripts'];
    }

    public function get_keywords(){
        return ['recent product view','recently view','recent view product'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content_general',
            [
                'label' => esc_html__( 'Settings', 'woolentor' ),
            ]
        );

            $this->add_control(
                'product_limit',
                [
                    'label'   => __( 'Product Limit', 'woolentor' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 4,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'order',
                [
                    'label' => esc_html__( 'Order', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','woolentor'),
                        'ASC'   => esc_html__('Ascending','woolentor'),
                    ]
                ]
            );

            $this->add_control(
                'slider_on',
                [
                    'label' => __( 'Slider On', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'=>'before',
                ]
            );

            $this->add_responsive_control(
                'grid_column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '4',
                    'options' => [
                        '1' => esc_html__( 'One', 'woolentor' ),
                        '2' => esc_html__( 'Two', 'woolentor' ),
                        '3' => esc_html__( 'Three', 'woolentor' ),
                        '4' => esc_html__( 'Four', 'woolentor' ),
                        '5' => esc_html__( 'Five', 'woolentor' ),
                        '6' => esc_html__( 'Six', 'woolentor' ),
                        '7' => esc_html__( 'Seven', 'woolentor' ),
                        '8' => esc_html__( 'Eight', 'woolentor' ),
                        '9' => esc_html__( 'Nine', 'woolentor' ),
                        '10'=> esc_html__( 'Ten', 'woolentor' ),
                    ],
                    'label_block' => true,
                    'prefix_class' => 'wl-columns%s-',
                    'condition'=>[
                        'slider_on!'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_empty_message',
                [
                    'label' => __( 'Show Empty Message', 'woolentor' ),
                    'type'  => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'empty_message',
                [
                    'label' => esc_html__( 'Empty Message', 'woolentor' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'rows' => 4,
                    'default' => esc_html__( 'You haven\'t viewed at any of the products yet.', 'woolentor' ),
                    'condition'=>[
                        'show_empty_message' => 'yes'
                    ]
                ]
            );

            $this->add_control(
                'same_height_box',
                [
                    'label'         => __( 'Same Height Box ?', 'woolentor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Yes', 'woolentor' ),
                    'label_off'     => __( 'No', 'woolentor' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                ]
            );

        $this->end_controls_section();

        // Slider Option
        $this->start_controls_section(
            'section_slider_option',
            [
                'label' => esc_html__( 'Slider Option', 'woolentor' ),
                'condition'=>[
                    'slider_on'=>'yes',
                ]
            ]
        );
            
            $this->add_control(
                'slitems',
                [
                    'label' => esc_html__( 'Slider Items', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 4
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => esc_html__( 'Slider Arrow', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => esc_html__( 'Slider dots', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'woolentor'),
                    'label_on' => __('Yes', 'woolentor'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'woolentor'),
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => esc_html__( 'Slider autoplay', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3000,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slanimation_speed',
                [
                    'label' => __('Autoplay animation speed', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 3,
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label' => __('Tablet Resolution', 'woolentor'),
                    'description' => __('The resolution to the tablet.', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label' => __('Mobile Resolution', 'woolentor'),
                    'description' => __('The resolution to mobile.', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_setting',
            [
                'label' => esc_html__( 'Content Settings', 'woolentor' ),
            ]
        );

            $this->add_control(
                'show_title',
                [
                    'label' => __( 'Show Title', 'woolentor' ),
                    'type'  => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_price',
                [
                    'label' => __( 'Show Price', 'woolentor' ),
                    'type'  => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_cart_button',
                [
                    'label' => __( 'Show Add To Cart Button', 'woolentor' ),
                    'type'  => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_badge',
                [
                    'label' => __( 'Show Badge', 'woolentor' ),
                    'type'  => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'title_html_tag',
                [
                    'label'   => __( 'Title HTML Tag', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => woolentor_html_tag_lists(),
                    'default' => 'h4',
                    'condition' => [
                        'show_title' => 'yes'
                    ]
                ]
            );

        $this->end_controls_section();

        // Item Style
        $this->start_controls_section(
            'item_area_style_section',
            array(
                'label' => __( 'Item Area', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_responsive_control(
                'item_area_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-viewed-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'item_area_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-recently-viewed-product',
                ]
            );

            $this->add_responsive_control(
                'item_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-viewed-product' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section(
            'content_area_style_section',
            array(
                'label' => __( 'Content Area', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_responsive_control(
                'content_align',
                [
                    'label'        => __( 'Alignment', 'woolentor' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'prefix_class' => 'elementor-align-%s',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-content' => 'text-align: {{VALUE}};',
                    ],
                    'default'      => 'left',
                ]
            );

            $this->add_responsive_control(
                'content_area_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'content_area_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-recently-view-content',
                ]
            );

            $this->add_responsive_control(
                'content_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
        
        $this->end_controls_section();

        $this->start_controls_section(
            'title_style_section',
            [
                'label' => __( 'Title', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title' => 'yes'
                ]
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .woolentor-recently-view-title',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-title a' => 'color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_control(
                'title_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-title a:hover' => 'color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'price_style_section',
            [
                'label' => __( 'Price', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_price' => 'yes'
                ]
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'price_typography',
                    'selector' => '{{WRAPPER}} .woolentor-recently-view-price span',
                ]
            );

            $this->add_control(
                'price_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-price span' => 'color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_responsive_control(
                'price_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'cart_button_style_section',
            [
                'label' => __( 'Add To Cart', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_cart_button' => 'yes'
                ]
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cart_button_typography',
                    'selector' => '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart)',
                ]
            );

            $this->add_responsive_control(
                'cart_button_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->start_controls_tabs('cart_button_style_tabs');

                // Normal
                $this->start_controls_tab(
                    'cart_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'cart_button_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart)' => 'color: {{VALUE}}',
                            ]
                        ]
                    );

                    $this->add_control(
                        'cart_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart)' => 'background-color: {{VALUE}}',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_button_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart)',
                        ]
                    );
        
                    $this->add_responsive_control(
                        'cart_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart)' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover
                $this->start_controls_tab(
                    'cart_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'cart_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart):hover' => 'color: {{VALUE}}',
                            ]
                        ]
                    );

                    $this->add_control(
                        'cart_button_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart):hover' => 'background-color: {{VALUE}}',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_button_hover_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart):hover',
                        ]
                    );
        
                    $this->add_responsive_control(
                        'cart_button_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-recently-view-content :is(.button, .added_to_cart):hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'badge_style_section',
            [
                'label' => __( 'Badge', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_badge' => 'yes'
                ]
            ]
        );

            $this->add_responsive_control(
                'badge_position',
                array(
                    'label'   => __( 'Position', 'woolentor' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => array(
                        'left'    => array(
                            'title' => __( 'Left', 'woolentor' ),
                            'icon'  => 'eicon-h-align-left',
                        ),
                        'right' => array(
                            'title' => __( 'Right', 'woolentor' ),
                            'icon'  => 'eicon-h-align-right',
                        ),
                    ),
                    'default'    => is_rtl() ? 'left' : 'right',
                    'selectors_dictionary' => array(
                        'left'   => 'right: auto; left:15px',
                        'right'  => 'left: auto; right:15px',
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .woolentor-recently-view-image .ht-product-label' => '{{VALUE}}',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'badge_typography',
                    'selector' => '{{WRAPPER}} .woolentor-recently-view-image .ht-product-label',
                ]
            );

            $this->add_control(
                'badge_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-image .ht-product-label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'outofstock_badge_color',
                [
                    'label' => __( 'Out of Stock Badge Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-image .ht-product-label.ht-stockout' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'badge_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-image .ht-product-label' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'badge_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-recently-view-image .ht-product-label',
                ]
            );

            $this->add_responsive_control(
                'badge_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-image .ht-product-label' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'badge_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-recently-view-image .ht-product-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'empty_message_style_section',
            [
                'label' => __( 'Empty Message', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_empty_message' => 'yes'
                ]
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'empty_message_typography',
                    'selector' => '{{WRAPPER}} .woolentor-no-view-product',
                ]
            );

            $this->add_control(
                'empty_message_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-no-view-product' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'empty_message_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-no-view-product' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'empty_message_box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-no-view-product',
                ]
            );

            $this->add_responsive_control(
                'empty_message_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-no-view-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'empty_message_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-no-view-product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Slider Button style
        $this->start_controls_section(
            'products-slider-controller-style',
            [
                'label' => esc_html__( 'Slider Controller Style', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

            $this->start_controls_tabs('product_sliderbtn_style_tabs');

                // Slider Button style Normal
                $this->start_controls_tab(
                    'product_sliderbtn_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'button_style_heading',
                        [
                            'label' => __( 'Navigation Arrow', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_responsive_control(
                        'nvigation_position',
                        [
                            'label' => __( 'Position', 'woolentor' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'top: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_style_dots_heading',
                        [
                            'label' => __( 'Navigation Dots', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_responsive_control(
                            'dots_position',
                            [
                                'label' => __( 'Position', 'woolentor' ),
                                'type' => Controls_Manager::SLIDER,
                                'size_units' => [ 'px', '%' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 1000,
                                        'step' => 1,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots' => 'left: {{SIZE}}{{UNIT}};',
                                ],
                            ]
                        );

                        $this->add_control(
                            'dots_bg_color',
                            [
                                'label' => __( 'Background Color', 'woolentor' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border',
                                'label' => __( 'Border', 'woolentor' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-dots li button',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius',
                            [
                                'label' => esc_html__( 'Border Radius', 'woolentor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Normal button style end

                // Button style Hover
                $this->start_controls_tab(
                    'product_sliderbtn_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'button_style_arrow_heading',
                        [
                            'label' => __( 'Navigation', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_control(
                        'button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_hover_bg_color',
                        [
                            'label' => __( 'Background', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );


                    $this->add_control(
                        'button_style_dotshov_heading',
                        [
                            'label' => __( 'Navigation Dots', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_control(
                            'dots_hover_bg_color',
                            [
                                'label' => __( 'Background Color', 'woolentor' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'background-color: {{VALUE}} !important;',
                                    '{{WRAPPER}} .product-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border_hover',
                                'label' => __( 'Border', 'woolentor' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-dots li button:hover',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius_hover',
                            [
                                'label' => esc_html__( 'Border Radius', 'woolentor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Hover button style end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Tab option end

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $same_height_box = $this->get_settings_for_display('same_height_box');

        $column = $settings['grid_column'];
        $collumval = 'wl-col-1';
        if( $column !='' ){
            $collumval = 'wl-col-'.$column;
        }

        $title_html_tag = woolentor_validate_html_tag( $settings['title_html_tag'] );

        $products_list = woolentor_get_track_user_data();

        if( Plugin::instance()->editor->is_edit_mode() && empty( $products_list ) ){
            echo '<div class="elementor-panel" style="margin-bottom:10px;"><div class="elementor-panel-alert elementor-panel-alert-warning">'. esc_html__( 'You haven\'t viewed at any of the products yet. Below are demo product for the editing mode.', 'woolentor' ) . '</div></div>';
        }else{
            if ( empty( $products_list ) ) {
                if( $settings['show_empty_message'] == 'yes' ){
                    echo '<div class="woolentor-no-view-product">'. esc_html( trim( $settings['empty_message'] )) .'</div>';
                }
                return '';
            }
        }


        // Slider Options
        $slider_main_div_style = '';
        if( $settings['slider_on'] === 'yes' ){

            $is_rtl = is_rtl();
            $direction = $is_rtl ? 'rtl' : 'ltr';
            $slider_settings = [
                'arrows' => ('yes' === $settings['slarrows']),
                'dots' => ('yes' === $settings['sldots']),
                'autoplay' => ('yes' === $settings['slautolay']),
                'autoplay_speed' => absint($settings['slautoplay_speed']),
                'animation_speed' => absint($settings['slanimation_speed']),
                'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
                'rtl' => $is_rtl,
            ];

            $slider_responsive_settings = [
                'product_items' => absint($settings['slitems']),
                'scroll_columns' => absint($settings['slscroll_columns']),
                'tablet_width' => absint($settings['sltablet_width']),
                'tablet_display_columns' => absint($settings['sltablet_display_columns']),
                'tablet_scroll_columns' => absint($settings['sltablet_scroll_columns']),
                'mobile_width' => absint($settings['slmobile_width']),
                'mobile_display_columns' => absint($settings['slmobile_display_columns']),
                'mobile_scroll_columns' => absint($settings['slmobile_scroll_columns']),

            ];
            $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );
            $slider_main_div_style = "style='display:none'";
        }else{
            $slider_settings = '';
        }

        $products_list_value = array_values( $products_list );

        if( $settings['order'] == 'DESC' ){
            $products_list_value = array_reverse( $products_list_value );
        }

        $args = array(
            'post_type'            => 'product',
            'ignore_sticky_posts'  => 1,
            'no_found_rows'        => 1,
            'posts_per_page'       => $settings['product_limit'],
            'orderby'              => 'post__in',
            'post__in'             => isset( $products_list_value ) ? $products_list_value : [],
        );
        $products = new \WP_Query( $args );
        
        //echo '<div class="wl-row '.( $settings['no_gutters'] === 'yes' ? 'wlno-gutters' : '' ).' '.( $settings['slider_on'] === 'yes' ? 'product-slider' : '' ).' " data-settings='.wp_json_encode( $slider_settings ).' '.$slider_main_div_style.'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        if ( $products->have_posts() ) {
            echo '<div class="wl-row '.( $same_height_box == 'yes' ? 'woolentor-product-same-height' : '' ).' '.( $settings['slider_on'] === 'yes' ? 'product-slider' : '' ).' " data-settings='.wp_json_encode( $slider_settings ).' '.$slider_main_div_style.'>';
            while( $products->have_posts() ): $products->the_post();
                ?>
                    <div class="<?php echo esc_attr( $collumval ); ?>">
                        <div class="woolentor-recently-viewed-product">
                            <div class="woolentor-recently-view-image">
                                <?php
                                    if( class_exists('WooCommerce') && $settings['show_badge'] == 'yes' ){ 
                                        woolentor_custom_product_badge(); 
                                        woolentor_sale_flash();
                                    }
                                ?>
                                <a href="<?php the_permalink();?>"> 
                                    <?php woocommerce_template_loop_product_thumbnail(); ?> 
                                </a>
                            </div>
                            
                            <?php if( $settings['show_title'] == 'yes' || $settings['show_price'] == 'yes' || $settings['show_cart_button'] == 'yes' ): ?>
                                <div class="woolentor-recently-view-content">
                                    <?php
                                        if( $settings['show_title'] == 'yes' ){
                                            echo sprintf( "<%s class='woolentor-recently-view-title'><a href='%s'>%s</a></%s>", $title_html_tag, esc_url(get_the_permalink()), get_the_title(), $title_html_tag ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        }
                                        if( $settings['show_price'] == 'yes' ){
                                            echo '<div class="woolentor-recently-view-price">';
                                                woocommerce_template_loop_price();
                                            echo '</div>';
                                        }
                                        if( $settings['show_cart_button'] == 'yes' ){
                                            woocommerce_template_loop_add_to_cart();
                                        }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
            endwhile;
            echo '</div>';
        }

    }

}