<?php
namespace Woolentor\Modules\EmailReports;
use WooLentor\Traits\ModuleBase;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Sales_Email_Reports{
    use ModuleBase;

    /**
     * Constructor
     */
    public function __construct(){
        $this->define_constants();
        $this->include();
        $this->init();
    }

    /**
     * Define Required Constants
     */
    public function define_constants(){
        define( 'Woolentor\Modules\EmailReports\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\EmailReports\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\EmailReports\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\EmailReports\MODULE_ASSETS', MODULE_URL . '/assets' );
        define( 'Woolentor\Modules\EmailReports\ENABLED', self::$_enabled );
    }

    /**
     * Include Required Files
     */
    public function include(){
        require_once( MODULE_PATH. "/includes/Functions.php" );
        require_once( MODULE_PATH. "/includes/classes/Admin.php" );
        require_once( MODULE_PATH. "/includes/classes/Email_Template.php" );
        require_once( MODULE_PATH. "/includes/classes/Report_Generator.php" );
        require_once( MODULE_PATH. "/includes/classes/Email_Generator.php" );
        require_once( MODULE_PATH. "/includes/classes/Schedule_Handler.php" );
    }

    /**
     * Initialize Module
     */
    public function init(){
        // Admin Instance
        if ( $this->is_request( 'admin' ) || $this->is_request( 'rest' ) ) {
            Admin::instance();
        }

        // Initialize schedule handler
        new Schedule_Handler();

        if( self::$_enabled ){
            // Hook into the cron action
            add_action('woolentor_email_reports_cron', [$this, 'send_scheduled_report']);
        }

    }

    /**
     * Send scheduled report
     */
    public function send_scheduled_report() {
        try {
            $report_generator = new Report_Generator();
            $email_generator = new Email_Generator();
    
            $report_data = $report_generator->generate();
    
            $email_content = $email_generator->generate($report_data);
    
            $recipients = woolentor_get_option('recipient_email', 'woolentor_email_reports_settings', '');
            $recipients = array_map('trim', explode(',', $recipients));
    
            if(!empty($recipients)) {
                $subject = sprintf('WooCommerce Sales Report - %s', date('Y-m-d H:i:s'));
                $headers = array('Content-Type: text/html; charset=UTF-8');
                
                foreach($recipients as $recipient) {
                    if(is_email($recipient)) {
                        wp_mail($recipient, $subject, $email_content, $headers);
                    }
                }
            }

        } catch(\Exception $e) {
            woolentor_email_reports_log('Error executing scheduled report: ' . $e->getMessage(), 'error');
        }
    }


}