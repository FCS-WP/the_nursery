<?php
namespace Woolentor\Modules\StoreVacation\Frontend;
use WooLentor\Traits\Singleton;
use \Woolentor\Modules\StoreVacation\Frontend as FrontendBase;
/**
 * Shortcode handler class
 */
class Shortcode {
    use Singleton;

    /**
     * Initializes the class
     */
    function __construct() {
        // Register Shortcode
        add_shortcode('woolentor_vacation_notice', [$this, 'vacation_notice_shortcode']);
    }

    /**
     * Vacation Notice Shortcode
     */
    public function vacation_notice_shortcode($atts){
        if( !FrontendBase::is_vacation_active() ){
            return '';
        }

        $atts = shortcode_atts(array(
            'message' => woolentor_get_option('vacation_message', 'woolentor_store_vacation_settings'),
            'class' => '',
        ), $atts);

        ob_start();
        ?>
        <div class="woolentor-store-vacation-notice <?php echo esc_attr($atts['class']); ?>">
            <?php echo wp_kses_post(FrontendBase::process_message_placeholders( $atts['message'] )); ?>
        </div>
        <?php
        return ob_get_clean();
    }


}