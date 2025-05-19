<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Currency_Switcher_Widget extends Widget_Base{
    public function get_name() {
        return 'woolentor_currency_switcher';
    }
    
    public function get_title() {
        return __( 'WL: Currency Switcher', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-exchange';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid'];
    }

    public function get_keywords(){
        return ['currency','multicurrency','multi','money','woolentor','shoplentor'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_general',
            [
                'label' => __( 'General', 'woolentor' ),
            ]
        );
            
            $this->add_control(
                'currency_style',
                [
                    'label'   => __( 'Style', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'dropdown',
                    'options' => [
                        'dropdown' => __( 'Dropdown', 'woolentor' ),
                        'list'     => __( 'List', 'woolentor' ),
                    ]
                ]
            );
            if( woolentor_is_pro() ){
                $this->add_control(
                    'show_flags',
                    [
                        'label'         => __( 'Show Currency flags ?', 'woolentor' ),
                        'type'          => Controls_Manager::SWITCHER,
                        'label_on'      => __( 'Yes', 'woolentor' ),
                        'label_off'     => __( 'No', 'woolentor' ),
                        'return_value'  => 'yes',
                        'default'       => 'yes',
                    ]
                );

                $this->add_control(
                    'flag_style',
                    [
                        'label'   => __( 'Flag Style', 'woolentor' ),
                        'description'=> __('This setting works only with the default flags.','woolentor'),
                        'type'    => Controls_Manager::SELECT,
                        'default' => 'circle',
                        'options' => [
                            'circle' => __( 'Circle', 'woolentor' ),
                            'square' => __( 'Square', 'woolentor' ),
                        ],
                        'condition' => [
                            'show_flags' => 'yes',
                        ],
                    ]
                );
            }else{
                $this->add_control(
                    'show_flags_pro',
                    [
                        'label'         => __( 'Show Currency flags ?', 'woolentor' ) .' <i class="eicon-pro-icon"></i>',
                        'type'          => Controls_Manager::SWITCHER,
                        'label_on'      => __( 'Yes', 'woolentor' ),
                        'label_off'     => __( 'No', 'woolentor' ),
                        'return_value'  => 'yes',
                        'default'       => 'no',
                        'classes' => 'woolentor-disable-control',
                    ]
                );

                $this->add_control(
                    'flag_style_pro',
                    [
                        'label'   => __( 'Flag Style', 'woolentor' ) .' <i class="eicon-pro-icon"></i>',
                        'type'    => Controls_Manager::SELECT,
                        'default' => 'circle',
                        'options' => [
                            'circle' => __( 'Circle', 'woolentor' ),
                        ],
                        'classes' => 'woolentor-disable-control',
                    ]
                );

                $this->add_control(
                    'flag_option_pro',
                    [
                        'type' => Controls_Manager::RAW_HTML,
                        'raw' => '<div style="line-height:18px;">Purchase our premium version to unlock all options! '.sprintf( __( '<a href="%s" target="_blank">Get Pro</a>', 'woolentor-pro' ), esc_url( 'https://woolentor.com/pricing/?utm_source=admin&utm_medium=editor&utm_campaign=free' ) ).'</div>',
                        'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                        'separator' => 'before'
                    ]
                );
            }

        $this->end_controls_section(); // General Settings

        // Currency Style Controls.
        $this->start_controls_section(
            'style_currency_section',
            [
                'label' => __( 'Currency', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'currency_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-currency-dropdown ul li:not(.active-currency)' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'currency_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-currency-dropdown ul li:not(.active-currency)',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'currency_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-currency-dropdown ul li + li',
                ]
            );

            $this->add_control(
                'currency_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-currency-dropdown ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'currency_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-currency-dropdown ul li:not(.active-currency):hover' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'currency_hover_bgcolor',
                [
                    'label' => __( 'Hover Background Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-currency-dropdown ul li:not(.active-currency):hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section(); // End Currency Style Controls.

        // Current Currency Style Controls.
        $this->start_controls_section(
            'style_current_currency_section',
            [
                'label' => __( 'Current Currency', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'currenct_currency_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-selected-currency-wrap span.woolentor-selected-currency' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woolentor-currency-dropdown.list-style ul li.active-currency' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'currenct_currency_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-selected-currency-wrap span.woolentor-selected-currency' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .woolentor-currency-dropdown.list-style ul li.active-currency' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'currency_style' => 'list',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'currenct_currency_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-selected-currency-wrap span.woolentor-selected-currency, {{WRAPPER}} .woolentor-currency-dropdown.list-style ul li.active-currency',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'currenct_currency_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-selected-currency-wrap, {{WRAPPER}} .woolentor-currency-dropdown.list-style ul li.active-currency',
                ]
            );

            $this->add_control(
                'dropdown_arrow_color',
                [
                    'label' => __( 'Arrow Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-currency-dropdown-arrow::after' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'currency_style' => 'dropdown',
                    ],
                ]
            );
        
        $this->end_controls_section(); // End Style Controls.

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $shortcode_attributes = [
            'style' => $settings['currency_style'],
        ];

        if( woolentor_is_pro() ){
            $shortcode_attributes['flags']      = $settings['show_flags'];
            $shortcode_attributes['flag_style'] = $settings['flag_style'];
        }

        echo woolentor_do_shortcode( 'woolentor_currency_switcher', $shortcode_attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }
}