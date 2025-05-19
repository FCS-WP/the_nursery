<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Vacation_Notice_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-vacation-notice';
    }
    
    public function get_title() {
        return __( 'WL: Vacation Notice', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-alert';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['woolentor-widgets'];
    }

    public function get_keywords(){
        return ['vacation','notice','store notice','vacation notice'];
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'vacation_notice_content',
            [
                'label' => esc_html__( 'Notice Content', 'woolentor' ),
            ]
        );
            
            $this->add_control(
                'custom_message',
                [
                    'label' => __( 'Custom Message', 'woolentor' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Enter custom message or leave empty for default', 'woolentor' ),
                ]
            );

        $this->end_controls_section();

        // Style
        $this->start_controls_section(
            'vacation_notice_style',
            [
                'label' => esc_html__( 'Notice Style', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'notice_color',
                [
                    'label' => __( 'Text Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-store-vacation-notice' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'notice_bgcolor',
                [
                    'label' => __( 'Background Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-store-vacation-notice' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'notice_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-store-vacation-notice',
                ]
            );

            $this->add_responsive_control(
                'notice_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-store-vacation-notice' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'notice_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-store-vacation-notice' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'notice_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-store-vacation-notice',
                ]
            );

            $this->add_responsive_control(
                'notice_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-store-vacation-notice' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $shortcode_attributes = [];
        
        if( !empty($settings['custom_message']) ){
            $shortcode_attributes['message'] = $settings['custom_message'];
        }

        echo woolentor_do_shortcode( 'woolentor_vacation_notice', $shortcode_attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

}