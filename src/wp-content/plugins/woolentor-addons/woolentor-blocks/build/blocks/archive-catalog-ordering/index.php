<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-archive-catalog-ordering-area', 'woolentor_archive_catalog_ordering' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

if( $block['is_editor'] ){
	echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
		woolentor_product_shorting('menu_order');
	echo '</div>';
} else{
	echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
		woocommerce_catalog_ordering();
	echo '</div>';
}