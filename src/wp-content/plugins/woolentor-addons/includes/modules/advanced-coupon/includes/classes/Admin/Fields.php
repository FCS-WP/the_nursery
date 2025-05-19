<?php
namespace Woolentor\Modules\AdvancedCoupon\Admin;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Fields {
    use Singleton;

    public function __construct(){
        add_filter( 'woolentor_admin_fields_vue', [ $this, 'admin_fields' ], 99, 1 );
    }

    /**
     * Admin Field Register
     * @param mixed $fields
     * @return mixed
     */
    public function admin_fields( $fields ){
        
        if( woolentor_is_pro() && method_exists( '\WoolentorPro\Modules\AdvancedCoupon\Admin\Fields', 'sitting_fields') ){
            array_splice( $fields['woolentor_others_tabs'], 13, 0, \WoolentorPro\Modules\AdvancedCoupon\Admin\Fields::instance()->sitting_fields() );
        }else{
            array_splice( $fields['woolentor_others_tabs'], 13, 0, $this->sitting_fields() );
        }

        return $fields;
    }

    /**
     * Settings Fields Fields;
     */
    public function sitting_fields(){
        
        $fields = [
            [
                'id'   => 'woolentor_advanced_coupon_settings',
                'name'  => esc_html__( 'Advanced Coupon', 'woolentor' ),
                'type'   => 'module',
                'default'=> 'off',
                'section'  => 'woolentor_advanced_coupon_settings',
                'option_id' => 'enable',
                'documentation' => esc_url('https://woolentor.com/doc/advanced-coupon/'),
                'require_settings'  => true,
                'setting_fields' => [
                    [
                        'id'    => 'enable',
                        'name'   => esc_html__( 'Enable / Disable', 'woolentor' ),
                        'desc'    => esc_html__( 'Enable / disable this module.', 'woolentor' ),
                        'type'    => 'checkbox',
                        'default' => 'off',
                        'class'   => 'woolentor-action-field-left'
                    ],
                    [
                        'id'    => 'url_couponp',
                        'name'   => esc_html__( 'URL Coupons', 'woolentor' ),
                        'desc'    => esc_html__( 'Enable / disable URL Coupons.', 'woolentor' ),
                        'type'    => 'checkbox',
                        'default' => 'off',
                        'class'   => 'woolentor-action-field-left',
                        'is_pro'  => true
                    ],
                    [
                        'id'    => 'url_coupon_slugp',
                        'name'   => esc_html__( 'URL Coupon Slug', 'woolentor' ),
                        'desc'    => esc_html__( 'You can change URL coupon slug from here.', 'woolentor' ),
                        'type'    => 'text',
                        'default' => 'discount',
                        'class'   => 'woolentor-action-field-left',
                        'is_pro'  => true
                    ]

                ]
            ]
        ];

        return $fields;

    }

}