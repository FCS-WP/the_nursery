<?php
namespace Woolentor\Modules\CurrencySwitcher\Admin;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Fields {
    use Singleton;

    public function __construct(){
        add_filter( 'woolentor_admin_fields_vue', [ $this, 'admin_fields' ], 99, 1 );
    }

    public function admin_fields( $fields ){
        if( woolentor_is_pro() && method_exists( '\WoolentorPro\Modules\CurrencySwitcher\Currency_Switcher', 'Fields') ){
            array_splice( $fields['woolentor_others_tabs'], 11, 0, \WoolentorPro\Modules\CurrencySwitcher\Currency_Switcher::instance()->Fields() );
        }else{
            array_splice( $fields['woolentor_others_tabs'], 11, 0, $this->currency_sitting_fields() );
        }

        $fields['woolentor_elements_tabs'][] = [
            'id'    => 'wl_currency_switcher',
            'name'   => esc_html__( 'Currency Switcher', 'woolentor' ),
            'type'    => 'element',
            'default' => 'on'
        ];

        // Block
        $fields['woolentor_gutenberg_tabs'][] = [
            'id'  => 'currency_switcher',
            'name' => esc_html__( 'Currency Switcher', 'woolentor' ),
            'type'  => 'element',
            'default' => 'on',
        ];

        return $fields;
    }

    /**
     * Currency Fields;
     */
    public function currency_sitting_fields(){
        $wc_currency = get_woocommerce_currency();
        $fields = array(
            array(
                'id'     => 'woolentor_currency_switcher',
                'name'    => esc_html__( 'Currency Switcher', 'woolentor' ),
                'type'     => 'module',
                'default'  => 'off',
                'section'  => 'woolentor_currency_switcher',
                'option_id'=> 'enable',
                'require_settings'  => true,
                'documentation' => esc_url('https://woolentor.com/doc/currency-switcher-for-woocommerce/'),
                'setting_fields' => array(
                    
                    array(
                        'id'  => 'enable',
                        'name' => esc_html__( 'Enable / Disable', 'woolentor' ),
                        'desc'  => esc_html__( 'You can enable / disable currency switcher from here.', 'woolentor' ),
                        'type'  => 'checkbox',
                        'default' => 'off',
                        'class'   =>'enable woolentor-action-field-left',
                    ),

                    array(
                        'id'        => 'woolentor_currency_list',
                        'name'       => esc_html__( 'Currency Switcher', 'woolentor' ),
                        'type'        => 'repeater',
                        'title_field' => 'currency',
                        'condition'   => [ 'key'=>'enable','operator'=> '==', 'value' => 'on' ],
                        'max_items'   => '2',
                        'message'     => [
                            'title' => esc_html__( 'Upgrade to Premium Version', 'woolentor' ),
                            'desc'  => esc_html__( 'With the free version, you can add 2 currencies. To unlock more currencies and advanced features, please upgrade to the pro version.', 'woolentor' ),
                            'pro_link' => esc_url('https://woolentor.com/pricing/?utm_source=admin&utm_medium=lockfeatures&utm_campaign=free'),
                        ],
                        'options' => [
                            'button_label' => esc_html__( 'Add Currency', 'woolentor' ),
                        ],
                        'action_button' => [
                            'label'    => esc_html__('Update Exchange Rates', 'woolentor'),
                            'callback' => 'woolentor_currency_exchange_rate', // Callback function name
                            'data'     => [
                                'action' => 'update_currency_exchange_rate_test' // We can pass any data to callback function if needed
                            ],
                            'option_id' => 'default_currency',
                            'update_key' => 'currency', // Field Update base on this field value.
                            'update_fields' => ['currency_excrate'], // Update these fields.
                            'message' => esc_html__( 'Currency exchange rate updated successfully.', 'woolentor' ),
                            'auto_save' => true,
                        ],
                        // Specify which fields should be updated based on repeater items
                        'update_fields' => [
                            [
                                'field_id' => 'default_currency', // ID of the field to update
                                'type' => 'select',
                                'value_key' => 'currency', // Repeater item field to use as option value
                                'label_key' => 'currency' // Repeater item field to use as option label
                            ]
                        ],
                        'fields'  => [

                            array(
                                'id'    => 'currency',
                                'name'   => esc_html__( 'Currency', 'woolentor' ),
                                'type'    => 'select',
                                'default' => $wc_currency,
                                'options' => woolentor_wc_currency_list(),
                                'class'   => 'woolentor-action-field-left wlcs-currency-selection wlcs-currency-selection-field',
                            ),

                            array(
                                'id'        => 'currency_decimal',
                                'name'       => esc_html__( 'Decimal', 'woolentor' ),
                                'type'        => 'number',
                                'default'     => 2
                            ),

                            array(
                                'id'    => 'currency_position',
                                'name'   => esc_html__( 'Currency Symbol Position', 'woolentor' ),
                                'type'    => 'select',
                                'default' => get_option( 'woocommerce_currency_pos' ),
                                'options' => array(
                                    'left'  => esc_html__('Left','woolentor'),
                                    'right' => esc_html__('Right','woolentor'),
                                    'left_space' => esc_html__('Left Space','woolentor'),
                                    'right_space' => esc_html__('Right Space','woolentor'),
                                ),
                            ),

                            array(
                                'id'        => 'currency_excrate',
                                'name'       => esc_html__( 'Exchange Rate', 'woolentor' ),
                                'type'        => 'number',
                                'default'     => 1,
                                'class'       => 'woolentor-action-field-left wlcs-currency-dynamic-exchange-rate',
                            ),

                            array(
                                'id'        => 'currency_excfee',
                                'name'       => esc_html__( 'Exchange Fee', 'woolentor' ),
                                'type'        => 'number',
                                'default'     => 0,
                                'class'       => 'woolentor-action-field-left',
                            ),

                            array(
                                'id'    => 'disallowed_payment_methodp',
                                'name'   => esc_html__( 'Payment Method Disables', 'woolentor' ),
                                'type'    => 'select',
                                'options' => array(
                                    'select' => esc_html__('This is a pro features','woolentor'),
                                ),
                                'class' => 'woolentor-action-field-left',
                                'is_pro'  => true,
                            ),

                            array(
                                'id'     => 'custom_currency_symbolp',
                                'name'   => esc_html__( 'Custom Currency Symbol', 'woolentor' ),
                                'type'    => 'text',
                                'class'   => 'woolentor-action-field-left',
                                'default' => esc_html__('This is a pro features','woolentor'),
                                'is_pro'  => true,
                            ),

                            array(
                                'id'    => 'custom_flagp',
                                'name'   => esc_html__( 'Custom Flag', 'woolentor' ),
                                'desc'    => esc_html__( 'You can upload your flag for currency switcher from here.', 'woolentor' ),
                                'type'    => 'imageupload',
                                'options' => [
                                    'button_label'        => esc_html__( 'Upload', 'woolentor' ),   
                                    'button_remove_label' => esc_html__( 'Remove', 'woolentor' ),   
                                ],
                                'class' => 'woolentor-action-field-left',
                                'is_pro'  => true,
                            ),

                        ],

                        'default' => array (
                            [
                                'currency'         => $wc_currency,
                                'currency_decimal' => 2,
                                'currency_position'=> get_option( 'woocommerce_currency_pos' ),
                                'currency_excrate' => 1,
                                'currency_excfee'  => 0
                            ],
                        ),

                    ),

                    array(
                        'id'    => 'default_currency',
                        'name'   => esc_html__( 'Default Currency', 'woolentor' ),
                        'type'    => 'select',
                        'options' => woolentor_added_currency_list(),
                        'default' => $wc_currency,
                        'class'   => 'woolentor-action-field-left wlcs-default-selection',
                        'condition'   => [ 'key'=>'enable','operator'=> '==', 'value' => 'on' ],
                    ),

                )
            )
        );

        return $fields;

    }

}