<?php
namespace Woolentor\Modules\StoreVacation;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Frontend handlers class
 */
class Frontend {
    use Singleton;
    
    /**
     * Initialize the class
     */
    private function __construct() {
        $this->includes();
        $this->init();
    }

    /**
     * Load Required files
     *
     * @return void
     */
    private function includes(){
        require_once( __DIR__. '/Frontend/Manage_Vacation.php' );
        require_once( __DIR__. '/Frontend/Shortcode.php' );
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function init(){
        Frontend\Manage_Vacation::instance();
        Frontend\Shortcode::instance();
    }

    /**
     * Check if vacation is active
     */
    public static function is_vacation_active(){
        $enabled = woolentor_get_option('enable', 'woolentor_store_vacation_settings');
        if( $enabled != 'on' ){
            return apply_filters( 'woolentor_vacation_access_allowed', false );
        }

        $start_date = woolentor_get_option('vacation_start_date', 'woolentor_store_vacation_settings');
        $end_date = woolentor_get_option('vacation_end_date', 'woolentor_store_vacation_settings');
        if( empty($start_date) || empty($end_date) ){
            return apply_filters( 'woolentor_vacation_access_allowed', false );
        }

        $current_time = current_time('timestamp');
        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);

        $is_active = ($current_time >= $start_date && $current_time <= $end_date);

        return apply_filters( 'woolentor_vacation_access_allowed', $is_active );
    }

    /**
     * Process message placeholders
     */
    public static function process_message_placeholders( $message ) {
        $start_date = woolentor_get_option('vacation_start_date', 'woolentor_store_vacation_settings');
        $end_date = woolentor_get_option('vacation_end_date', 'woolentor_store_vacation_settings');
        
        if( empty($start_date) || empty($end_date) ){
            return $message;
        }

        // Format dates using WordPress date format
        $formatted_start = date_i18n( get_option('date_format'), strtotime($start_date) );
        $formatted_end = date_i18n( get_option('date_format'), strtotime($end_date) );

        // Calculate days remaining
        $current_time = current_time('timestamp');
        $end_time = strtotime($end_date);
        $days_remaining = ceil(($end_time - $current_time) / (60 * 60 * 24));
        $days_remaining = max(0, $days_remaining);

        $placeholders = array(
            '{start_date}' => $formatted_start,
            '{end_date}' => $formatted_end,
            '{days_remaining}' => $days_remaining
        );

        return str_replace(array_keys($placeholders), array_values($placeholders), $message);
    }

    

}