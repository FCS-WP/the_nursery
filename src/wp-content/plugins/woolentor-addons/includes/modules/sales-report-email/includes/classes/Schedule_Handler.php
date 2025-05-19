<?php
namespace Woolentor\Modules\EmailReports;

/**
 * Schedule Handler class
 */
class Schedule_Handler {

    /**
     * Constructor
     */
    public function __construct() {
        // Add custom schedules
        add_filter('cron_schedules', [$this, 'add_custom_schedules']);

        // Setup schedule
        add_action('init', [$this, 'setup_schedule']);

        // Re-Schedule when settings updated
        add_action('woolentor_save_option_woolentor_email_reports_settings', [$this, 'reschedule_reports']);
    }

    /**
     * Add custom schedules
     */
    public function add_custom_schedules($schedules) {
        // Get minutes from settings
        $minutes = (int) woolentor_get_option('custom_minutes', 'woolentor_email_reports_settings', 30);
        $minutes = max(5, $minutes); // Minimum 5 minutes

        // Add custom schedule
        $schedules['woolentor_custom_minutes'] = array(
            'interval' => $minutes * 60,
            'display' => sprintf(__('Every %d minutes', 'woolentor'), $minutes)
        );

        return $schedules;
    }

    /**
     * Setup schedule
     */
    public function setup_schedule() {
        // Check if module is enabled
        if(woolentor_get_option('enable', 'woolentor_email_reports_settings', 'off') != 'on') {
            $this->clear_schedule();
            return;
        }

        $schedule_type = woolentor_get_option('schedule_type', 'woolentor_email_reports_settings', 'daily');
        
        // If schedule doesn't exist, create it
        if (!wp_next_scheduled('woolentor_email_reports_cron')) {
            $next_run = $this->calculate_next_run();
            $recurrence = $this->get_schedule_recurrence($schedule_type);
            wp_schedule_event($next_run, $recurrence, 'woolentor_email_reports_cron');
        }
    }

    /**
     * Get schedule recurrence
     */
    private function get_schedule_recurrence($schedule_type) {
        switch($schedule_type) {
            case 'custom':
                return 'woolentor_custom_minutes';
            case 'hourly':
                return 'hourly';
            case 'weekly':
                return 'weekly';
            case 'monthly':
                return 'monthly';
            default:
                return 'daily';
        }
    }

    /**
     * Calculate next run time
     */
    private function calculate_next_run() {
        $schedule_type = woolentor_get_option('schedule_type', 'woolentor_email_reports_settings', 'daily');
        $current_time = current_time('timestamp',1);

        switch($schedule_type) {
            case 'custom':
                $minutes = (int) woolentor_get_option('custom_minutes', 'woolentor_email_reports_settings', 30);
                $minutes = max(5, $minutes);
                return $current_time + ($minutes * 60);

            case 'hourly':
                return strtotime('+1 hour', $current_time);

            case 'daily':
                $time = woolentor_get_option('schedule_time', 'woolentor_email_reports_settings', '00:00');
                return strtotime('tomorrow ' . $time, $current_time);

            case 'weekly':
                $day = woolentor_get_option('week_day', 'woolentor_email_reports_settings', '1');
                $time = woolentor_get_option('schedule_time', 'woolentor_email_reports_settings', '00:00');
                return strtotime('next ' . $this->get_day_name($day) . ' ' . $time, $current_time);

            case 'monthly':
                $day = woolentor_get_option('month_day', 'woolentor_email_reports_settings', '1');
                $time = woolentor_get_option('schedule_time', 'woolentor_email_reports_settings', '00:00');
                return strtotime(date('Y-m-' . $day . ' ' . $time, strtotime('+1 month', $current_time)));

            default:
                return strtotime('+1 day', $current_time);
        }
    }

    /**
     * Reschedule reports
     */
    public function reschedule_reports() {
        $this->clear_schedule();
        $this->setup_schedule();
    }

    /**
     * Clear existing schedule
     */
    public function clear_schedule() {
        $timestamp = wp_next_scheduled('woolentor_email_reports_cron');
        if($timestamp) {
            wp_unschedule_event($timestamp, 'woolentor_email_reports_cron');
        }
    }

    /**
     * Get day name
     */
    private function get_day_name($day) {
        $days = array(
            '1' => 'monday',
            '2' => 'tuesday',
            '3' => 'wednesday',
            '4' => 'thursday',
            '5' => 'friday',
            '6' => 'saturday',
            '7' => 'sunday'
        );
        return isset($days[$day]) ? $days[$day] : 'monday';
    }

    /**
     * Get next run info
     */
    public function get_next_run_info() {
        $timestamp = wp_next_scheduled('woolentor_email_reports_cron');
        if(!$timestamp) {
            return __('Not scheduled', 'woolentor');
        }

        $schedule_type = woolentor_get_option('schedule_type', 'woolentor_email_reports_settings', 'daily');
        $date_format = get_option('date_format') . ' ' . get_option('time_format');
        $schedule = wp_get_schedule('woolentor_email_reports_cron');
        
        return sprintf(
            __('Next run: %s (Schedule: %s)', 'woolentor'),
            date_i18n($date_format, $timestamp),
            $schedule
        );
    }
}