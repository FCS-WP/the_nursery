<?php
namespace Woolentor\Modules\EmailReports\Admin;
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
        
        array_splice( $fields['woolentor_others_tabs'], 16, 0, $this->sitting_fields() );

        return $fields;
    }

    /**
     * Settings Fields Fields;
     */
    public function sitting_fields(){
        
        $fields = array(
            array(
                'id'     => 'woolentor_email_reports_settings',
                'name'    => esc_html__( 'Sales Report Email', 'woolentor' ),
                'type'     => 'module',
                'default'  => 'off',
                'section'  => 'woolentor_email_reports_settings',
                'option_id'=> 'enable',
                'require_settings'  => true,
                'documentation' => esc_url('https://woolentor.com/doc/sales-report-email-module-in-woocommerce/'),
                'setting_fields' => array(
                    array(
                        'id'  => 'enable',
                        'name' => esc_html__( 'Enable / Disable', 'woolentor' ),
                        'desc'  => esc_html__( 'Enable/disable email reports module', 'woolentor' ),
                        'type'  => 'checkbox',
                        'default' => 'off',
                        'class' => 'woolentor-action-field-left'
                    ),

                    array(
                        'id'    => 'schedule_type',
                        'name'   => esc_html__( 'Report Schedule', 'woolentor' ),
                        'desc'    => esc_html__( 'Select report frequency', 'woolentor' ),
                        'type'    => 'select',
                        'default' => 'daily',
                        'options' => array(
                            'custom'  => esc_html__('Custom Minutes', 'woolentor'),
                            'hourly'  => esc_html__('Hourly', 'woolentor'),
                            'daily'   => esc_html__('Daily', 'woolentor'),
                            'weekly'  => esc_html__('Weekly', 'woolentor'),
                            'monthly' => esc_html__('Monthly', 'woolentor')
                        ),
                        'class' => 'woolentor-action-field-left'
                    ),

                    // Add custom minutes field
                    array(
                        'id'    => 'custom_minutes',
                        'name'   => esc_html__( 'Custom Minutes', 'woolentor' ),
                        'desc'    => esc_html__( 'Enter minutes (minimum 5 minutes)', 'woolentor' ),
                        'type'    => 'number',
                        'min'     => 5,
                        'default' => 30,
                        'class'   => 'woolentor-action-field-left',
                        'condition' => array( 'key'=>'schedule_type','operator'=> '==', 'value'=>'custom' )
                    ),

                    // Add time field for daily, weekly, monthly schedules
                    array(
                        'id'    => 'schedule_time',
                        'name'   => esc_html__( 'Time', 'woolentor' ),
                        'desc'    => esc_html__( 'Set time (24-hour format)', 'woolentor' ),
                        'type'    => 'text',
                        'default' => '00:00',
                        'class'   => 'woolentor-action-field-left',
                        'condition' => array( 'key'=>'schedule_type', 'operator'=> 'any', 'value'=>'daily,weekly,monthly' )
                    ),

                    // Add day selection for weekly schedule
                    array(
                        'id'    => 'week_day',
                        'name'   => esc_html__( 'Day of Week', 'woolentor' ),
                        'desc'    => esc_html__( 'Select day of the week', 'woolentor' ),
                        'type'    => 'select',
                        'options' => array(
                            '1' => esc_html__('Monday', 'woolentor'),
                            '2' => esc_html__('Tuesday', 'woolentor'),
                            '3' => esc_html__('Wednesday', 'woolentor'),
                            '4' => esc_html__('Thursday', 'woolentor'),
                            '5' => esc_html__('Friday', 'woolentor'),
                            '6' => esc_html__('Saturday', 'woolentor'),
                            '7' => esc_html__('Sunday', 'woolentor')
                        ),
                        'default' => '1',
                        'class'   => 'woolentor-action-field-left',
                        'condition' => array( 'key'=>'schedule_type', 'operator'=>'==', 'value'=>'weekly' )
                    ),

                    // Add day selection for monthly schedule
                    array(
                        'id'    => 'month_day',
                        'name'   => esc_html__( 'Day of Month', 'woolentor' ),
                        'desc'    => esc_html__( 'Select day of the month', 'woolentor' ),
                        'type'    => 'number',
                        'min'     => 1,
                        'max'     => 31,
                        'default' => 1,
                        'class'   => 'woolentor-action-field-left',
                        'condition' => array( 'key'=>'schedule_type', 'operator'=>'==', 'value'=>'monthly' )
                    ),
            
                    array(
                        'id'    => 'recipient_email',
                        'name'   => esc_html__( 'Recipients', 'woolentor' ),
                        'desc'    => esc_html__( 'Enter email addresses (comma-separated)', 'woolentor' ),
                        'type'    => 'text',
                        'default' => get_option('admin_email'),
                        'class'   => 'woolentor-action-field-left'
                    ),
            
                    array(
                        'id'    => 'report_metrics',
                        'name'   => esc_html__( 'Report Metrics', 'woolentor' ),
                        'desc'    => esc_html__( 'Select metrics to include in report', 'woolentor' ),
                        'type'    => 'multiselect',
                        'default' => array('sales', 'orders'),
                        'options' => array(
                            'sales'       => esc_html__('Sales', 'woolentor'),
                            'orders'      => esc_html__('Orders', 'woolentor'),
                            'top_products'=> esc_html__('Top Products', 'woolentor')
                        ),
                        'class' => 'woolentor-action-field-left'
                    )
                )
            )
        );

        return $fields;

    }

}