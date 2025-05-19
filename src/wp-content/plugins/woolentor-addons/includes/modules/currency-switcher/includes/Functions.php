<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get Save Currency list
 */
function woolentor_currency_list(){
    $currency_list = woolentor_get_option( 'woolentor_currency_list', 'woolentor_currency_switcher', [] );
    if( !empty( $currency_list ) && is_array( $currency_list )){
        if( !woolentor_is_pro() ){
            return isset( $currency_list[1] ) ? [ $currency_list[0], $currency_list[1] ] : [ $currency_list[0] ];
        }
        return $currency_list;
    }
    return [];
}

/**
 * Get Default Currency
 */
function woolentor_default_currency(){
    $wc_currency   = get_woocommerce_currency();
    $default_currency = woolentor_get_option( 'default_currency', 'woolentor_currency_switcher', $wc_currency );
    return !empty( $default_currency ) ? $default_currency : $wc_currency;
}

/**
 * Get Current Currency code
 */
function woolentor_current_currency_code(){

    $currnet_user_id = get_current_user_id();
    $currency_code   = '';

    if ( $currnet_user_id && get_user_meta( $currnet_user_id ,'woolentor_current_currency_code' ) ) {
        $currency_code  = get_user_meta( $currnet_user_id ,'woolentor_current_currency_code', true );
    }
    elseif ( isset( $_COOKIE['woolentor_current_currency_code'] ) ) {
        $currency_code = sanitize_text_field( $_COOKIE['woolentor_current_currency_code'] );
    }
    else {
        $currency_code = woolentor_default_currency();
    }

    return $currency_code;
}

/**
 * WooCommerce Currency Symbol
 */
function woolentor_currency_symbol( $currency ){

    $currency_symbol = ( isset( $currency['custom_currency_symbol'] ) && $currency['custom_currency_symbol'] !== '' && woolentor_is_pro() ) ? $currency['custom_currency_symbol'] : woolentor_wc_currency_symbol( $currency['currency'] );

    return $currency_symbol;
}

/**
 * WooCommerce Currency List
 */
function woolentor_wc_currency_list(){
    $wc_currencie_list = get_woocommerce_currencies();
    foreach ( $wc_currencie_list as $code => $name ) {
        $currency = woolentor_current_currency($code);
        $currency_symbol = ( isset( $currency['custom_currency_symbol'] ) && $currency['custom_currency_symbol'] !== '' && woolentor_is_pro() ) ? $currency['custom_currency_symbol'] : woolentor_wc_currency_symbol( $code );

        $wc_currencie_list[ $code ] = $name . ' (' .$currency_symbol. ')';
    }
    return $wc_currencie_list;
}

/**
 * Generate option list with all save Currency
 */
function woolentor_added_currency_list(){
    $woolentor_currencie_list = woolentor_currency_list();
    $wc_currencie_list = get_woocommerce_currencies();
    $generate_list = [];
    
    if( count( $woolentor_currencie_list ) > 0 ){
        foreach ( $woolentor_currencie_list as $item ) {
            $currency = woolentor_current_currency( $item['currency'] );
            $currency_symbol = woolentor_currency_symbol( $currency );
            $generate_list[ $item['currency'] ] = $wc_currencie_list[$item['currency']] . ' (' . $currency_symbol . ')';
        }
    }else{
        $default_code = get_woocommerce_currency();
        $currency_symbol = woolentor_wc_currency_symbol( $default_code );
        $generate_list[ $default_code ] = $wc_currencie_list[$default_code] . ' (' . $currency_symbol . ')';
    }
    return $generate_list;
}

/**
 * Currency Currency
 *
 * @param [type] $code
 */
function woolentor_current_currency( $code ){
    $currency_list = woolentor_currency_list();
    $currenct_currency_key = array_search( $code, array_column( $currency_list, 'currency' ) );
    return isset( $currency_list[$currenct_currency_key] ) ? $currency_list[$currenct_currency_key] : [];
}

/**
 * Get Currency symbol
 *
 * @param [type] $currency
 */
function woolentor_wc_currency_symbol( $currency = '' ) {
	if ( ! $currency ) {
		$currency = get_woocommerce_currency();
	}

	$symbols = get_woocommerce_currency_symbols();

	$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

	return $currency_symbol;
}

/**
 * Convert Currency
 *
 * @param [type] $args
 * @return void
 */
function woolentor_currency_exchange_rate( $args ){
    return \Woolentor\Modules\CurrencySwitcher\Admin::instance()->get_exchange_rate( $args );
}