<?php
namespace Woolentor\Modules\CurrencySwitcher\Admin;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Actions {
    use Singleton;

    /**
     * Class Constructor
     */
    public function __construct(){
        add_filter( 'woocommerce_general_settings', [ $this, 'woocommerce_general_settings' ] );
    }

    /**
     *  Remove Default currency, decimals and Currency postion setting from WooCommerce General Settins
     */
    public function woocommerce_general_settings ( $fields ) {
        $general_fields_remove = [ 'woocommerce_currency', 'woocommerce_price_num_decimals', 'woocommerce_currency_pos' ];
        foreach( $fields as $field_key => $field_data ) {
            if( isset( $field_data['id'] ) ) {
                if( in_array( $field_data['id'] , $general_fields_remove ) ) {
                    unset( $fields[$field_key] );
                }
                if( $field_data['id'] == 'pricing_options' ) {
                    $fields[$field_key]['desc'] = esc_html__('Looks like ShopLentor\'s Currency Switcher Module is enabled. To configure the currency-related options, please go to the module\'s settings. ', 'woolentor'). '<a href="'.get_admin_url().'/admin.php?page=woolentor'.'" target="blank">'. esc_html__( 'Module Setting page', 'woolentor' ).'</a>';
                }
            }
        }
        return $fields;
    }

}