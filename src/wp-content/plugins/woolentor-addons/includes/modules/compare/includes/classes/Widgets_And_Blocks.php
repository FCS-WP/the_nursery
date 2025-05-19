<?php
namespace EverCompare;
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

        $block_list['ever_compare_table'] = [
            'label'  => __('Ever Compare Table','woolentor'),
            'name'   => 'woolentor/ever-compare-table',
            'server_side_render' => true,
            'type'   => 'common',
            'active' => true,
            'is_pro' => false,
            'location' => EVERCOMPARE_BLOCKS_PATH,
        ];

        return $block_list;
    }

}