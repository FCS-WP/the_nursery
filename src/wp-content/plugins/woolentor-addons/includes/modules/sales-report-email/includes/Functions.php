<?php

/**
 * Log handler for email reports
 */
function woolentor_email_reports_log($message, $type = 'info') {
    $log_file = WP_CONTENT_DIR . '/woolentor-email-reports.log';
    $timestamp = current_time('Y-m-d H:i:s');
    $log_message = sprintf("[%s] [%s] %s\n", $timestamp, strtoupper($type), $message);
    error_log($log_message, 3, $log_file);
}