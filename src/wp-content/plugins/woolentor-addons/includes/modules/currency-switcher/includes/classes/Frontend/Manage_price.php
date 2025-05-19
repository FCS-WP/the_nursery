<?php
namespace Woolentor\Modules\CurrencySwitcher\Frontend;
use WooLentor\Traits\Singleton;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Manage_Price {
    use Singleton;

    private $currency_code = 'USD';
	private $decimal;
    private $currency_symbol;
	private $symbol_position;
    private $exchange_rate;
    private $exchange_fee;
	private $disallowed_payment_method;

    public function __construct(){
        $this->set_current_currency();

        add_filter('woocommerce_currency', [ $this, 'set_currency_code' ] );
        add_filter('wc_get_price_decimals', [ $this, 'price_decimals' ] );
		add_filter('woocommerce_price_format', [ $this, 'price_format' ], 999 );
        
        // Product price
        add_filter('woocommerce_product_get_price', [ $this, 'product_price_modifier' ] );
        add_filter('woocommerce_product_get_regular_price', [ $this, 'product_regular_price_modifier' ] );
		add_filter('woocommerce_product_get_sale_price', [ $this, 'product_sale_price_modifier' ] );
        // Variation prices
		add_filter('woocommerce_variation_prices', [ $this, 'variation_product_regular_price_modifier' ], 999 );
        // Variable price
		add_filter('woocommerce_product_variation_get_price', [ $this, 'variation_product_price_modifier' ], 999 );
		add_filter('woocommerce_product_variation_get_regular_price', [ $this, 'variation_product_price_modifier' ], 999 );
		add_filter('woocommerce_product_variation_get_sale_price', [ $this, 'variation_product_price_modifier' ], 999 );
        // Coupon Amount
        add_action('woocommerce_coupon_loaded', [ $this, 'coupon_amount_modifier' ], 999 );
        // Shipping const
		add_filter('woocommerce_shipping_packages', [$this, 'shipping_cost_modifier']);
        if( woolentor_is_pro() ){
            add_filter( 'woocommerce_currency_symbol', [ $this, 'currency_symbol' ], 99999 );
            // Payment gatways
            if( !is_admin() ){
                add_filter('woocommerce_available_payment_gateways', [ $this, 'available_payment_gateways_modifier' ] );
            }
        }
    }

    /**
	 * Set Current Currency Switcher Property
	 * @return void
	 */
    private function set_current_currency() {
		$this->currency_code = woolentor_current_currency_code();
        $current_currency = woolentor_current_currency( $this->currency_code );
		$this->currency_symbol = (isset( $current_currency['custom_currency_symbol'] ) && $current_currency['custom_currency_symbol'] !== '') ? $current_currency['custom_currency_symbol'] : get_woocommerce_currency_symbol( $this->currency_code );
        $this->exchange_rate = (int)( $current_currency['currency_excrate'] > 0 ) ? $current_currency['currency_excrate'] : 1;
        $this->exchange_fee = (int)( $current_currency['currency_excfee'] >= 0 ) ? $current_currency['currency_excfee'] : 0;
        $this->decimal = isset( $current_currency['currency_decimal'] ) ? $current_currency['currency_decimal'] : '';
        $this->symbol_position = isset( $current_currency['currency_position'] ) ? $current_currency['currency_position'] : '';
        $this->disallowed_payment_method = isset( $current_currency['disallowed_payment_method'] ) ? $current_currency['disallowed_payment_method'] : '';
    }

    /**
     * Set Currency Code
     */
    public function set_currency_code(){
        return strtoupper( $this->currency_code );
    }

    /**
	 * Modify WC price decimals
	 */
    public function price_decimals( $wc_decimal ) {
        if( $this->decimal || $this->decimal == '0' ) {
            return $this->decimal;
        }
        else {
            return $wc_decimal;
        }
    }

    /**
	 * Modify WC price format
	 */
    public function price_format() {
        $currency_position = $this->symbol_position;
        $format = '%1$s%2$s';
        switch ( $currency_position ) {
            case 'left':
                $format = '%1$s%2$s';
                break;
            case 'right':
                $format = '%2$s%1$s';
                break;
            case 'left_space':
                $format = '%1$s&nbsp;%2$s';
                break;
            case 'right_space':
                $format = '%2$s&nbsp;%1$s';
                break;
        }
        return $format;
    }

    /**
	 * Modify Currency symbol
	 */
    public function currency_symbol( $currency_symbol, $currency = '' ){
        $currency_symbol = $this->currency_symbol;
        return $currency_symbol;
    }

    /**
     * Calculate Price
     *
     * @param [type] $value
     */
    public function calculate_price( $value ) {
        if( !empty( $value ) ) {
            if( $this->currency_code != woolentor_default_currency() ) {
                return ( ( $this->exchange_rate + $this->exchange_fee ) * (float)$value );
            }
        }
        return $value;
    }

    /**
     * Modify Product Price
     */
    public function product_price_modifier( $price ) {
		return $this->calculate_price( $price );
    }

    /**
	 * Modify Products Regular Price
	 */
    public function product_regular_price_modifier( $price ) {
		return $this->calculate_price( $price );
    }

    /**
	 * Modify Products Sale Price
	 */
    public function product_sale_price_modifier( $price ) {
		return $this->calculate_price( $price );
    }

    /**
	 * Modify Variation Products Price
	 */
    public function variation_product_regular_price_modifier( $prices ) {
		$new_price = [];
        foreach( $prices as $key => $values) {
            foreach( $values as $mn => $val ) {
                $new_price[$key][$mn] = $this->calculate_price( $val );
            }
        }
		if( empty( $new_price ) ) {
            return $prices;
        }
        return $new_price;
	}

    /**
	 * Modify Variation Products Price
	 */
    public function variation_product_price_modifier( $price ) {
		return $this->calculate_price( $price );
	}

    /**
	 * Modify Products Coupon Amount
	 */
    public function coupon_amount_modifier( $coupon ) {
        $type = $coupon->get_discount_type();
        if( $type === 'fixed_cart' || $type === 'fixed_product' ) {
            $coupon->set_amount( $this->calculate_price( $coupon->get_amount() ) );
        }
		return $coupon;
	}

    /**
	 * Modify Shipping const
	 */
    public function shipping_cost_modifier($packages){
		foreach( $packages as $package ) {
			foreach( $package['rates'] as $rate ) {
				$rate->set_cost( $this->calculate_price( $rate->get_cost() ) );
			}
		}
		return $packages;
	}

    /**
	 * Exclude disallowed payment method
	 */
    public function available_payment_gateways_modifier( $available_gate_ways ) {
        if( is_array( $this->disallowed_payment_method ) ) {
            foreach ( $this->disallowed_payment_method as $gateway ) {
                unset( $available_gate_ways[$gateway] );
            }
        }
        return $available_gate_ways;
    }

}