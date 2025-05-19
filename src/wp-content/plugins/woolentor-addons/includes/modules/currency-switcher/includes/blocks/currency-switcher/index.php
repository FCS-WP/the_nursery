<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-currency-switcher-area' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';


echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
    $shortcode_attributes = [
        'style' => $settings['currencyStyle'],
    ];
    if( woolentor_is_pro() ){
        $shortcode_attributes['flags']      = $settings['showFlags'];
        $shortcode_attributes['flag_style'] = $settings['flagStyle'];
    }
    echo woolentor_do_shortcode( 'woolentor_currency_switcher', $shortcode_attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '</div>';