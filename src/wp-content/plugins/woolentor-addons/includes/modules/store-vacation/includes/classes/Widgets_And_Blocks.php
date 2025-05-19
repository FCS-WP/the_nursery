<?php
namespace Woolentor\Modules\StoreVacation;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Widgets class.
 */
class Widgets_And_Blocks {
    use Singleton;

	/**
     * Widgets constructor.
     */
    public function __construct() {

        // Elementor Widget
        add_filter( 'woolentor_widget_list', [ $this, 'widget_list' ] );

        // Guttenberg Block
        // add_filter('woolentor_block_list', [ $this, 'block_list' ] );

    }

    /**
     * Widget list.
     */
    public function widget_list( $widget_list = [] ) {
        
        $widget_list['common']['wl_vacation_notice'] = [
            'title'    => esc_html__('Vacation Notice','woolentor'),
            'location' => WIDGETS_PATH,
        ];

        return $widget_list;
    }

    /**
     * Block list.
     */
    public function block_list( $block_list = [] ){

        $block_list['wl_vacation_notice'] = [
            'label'  => __('Vacation Notice','woolentor'),
            'name'   => 'woolentor/vacation-notice',
            'server_side_render' => true,
            'type'   => 'common',
            'active' => true,
            'location' => BLOCKS_PATH,
        ];

        return $block_list;
    }

}