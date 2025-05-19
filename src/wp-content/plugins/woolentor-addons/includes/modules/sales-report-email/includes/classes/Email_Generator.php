<?php
namespace Woolentor\Modules\EmailReports;

/**
 * Email Generator class
 */
class Email_Generator {

    /**
     * Generate email content
     */
    public function generate($report_data) {
        ob_start();
        include MODULE_PATH . '/includes/views/email-template.php';
        return ob_get_clean();
    }
}