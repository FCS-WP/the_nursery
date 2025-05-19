<?php
namespace Woolentor\Modules\SalesNotification;
use WooLentor\Traits\ModuleBase;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Sales_Notification{
    use ModuleBase;

    /**
     * Class Constructor
     */
    public function __construct(){

        // Include Nessary file
        $this->include();

    }

    /**
     * Load Required File
     *
     * @return void
     */
    public function include(){
        if( self::$_enabled && $this->is_request( 'frontend' ) ){
            if( woolentor_get_option( 'notification_content_type', 'woolentor_sales_notification_tabs', 'actual' ) == 'fakes' ){
                require_once( __DIR__. '/manual_notification.php' );
            }else{
                require_once( __DIR__. '/real_notification.php' );
            }
        }
    }

}
Sales_Notification::instance(true);