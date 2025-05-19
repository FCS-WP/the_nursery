<?php
namespace WishSuite;
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

        // Guttenberg Block
        add_filter('woolentor_block_list', [ $this, 'block_list' ] );

    }

    /**
     * Block list.
     */
    public function block_list( $block_list = [] ){

        $block_list['wishsuite_table'] = [
            'label'  => __('WishSuite Table','woolentor'),
            'name'   => 'woolentor/wishsuite-table',
            'server_side_render' => true,
            'type'   => 'common',
            'active' => true,
            'is_pro' => false,
            'location' => WISHSUITE_BLOCKS_PATH,
        ];

        $block_list['wishsuite_counter'] = [
            'label'  => __('WishSuite Counter','woolentor'),
            'name'   => 'woolentor/wishsuite-counter',
            'server_side_render' => true,
            'type'   => 'common',
            'active' => true,
            'is_pro' => false,
            'location' => WISHSUITE_BLOCKS_PATH,
        ];

        return $block_list;
    }

}