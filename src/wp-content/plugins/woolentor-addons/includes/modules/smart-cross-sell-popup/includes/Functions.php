<?php
/**
 * Smart Cross-sell Popup Functions
 */

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

/**
 * Get module settings
 */
function woolentor_smart_cross_sell_get_settings(){
    $settings = get_option( 'woolentor_smart_cross_sell_popup_settings', [] );
    return wp_parse_args( $settings, [
        'enable'        => 'off',
        'popup_title'   => esc_html__( 'You May Also Like', 'woolentor' ),
        'product_limit' => '4',
        'popup_width'   => '500px',
        'button_color'  => '#333333',
        'button_hover_color' => '#515151'
    ]);
}

/**
 * Check if module is enabled
 */
function woolentor_is_smart_cross_sell_enabled(){
    $settings = woolentor_smart_cross_sell_get_settings();
    return isset( $settings['enable'] ) && $settings['enable'] === 'on';
}

/**
 * Check if feature is pro
 */
function woolentor_is_cross_sell_pro_feature($feature){
    $pro_features = [
        'exit_intent_trigger',
        'time_delay_trigger',
        'scroll_trigger',
        'checkout_trigger',
        'custom_products',
        'advanced_analytics',
        'multiple_layouts'
    ];
    return in_array($feature, $pro_features);
}