<?php
namespace Woolentor\Modules\FlashSale;
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

        $block_list['product_flash_sale'] = [
            'label'  => __('Product Flash Sale','woolentor'),
            'name'   => 'woolentor/product-flash-sale',
            'server_side_render' => true,
            'type'   => 'common',
            'active' => true,
            'is_pro' => false,
            'enqueue_assets'=> function(){
                wp_enqueue_style('woolentor-flash-sale-module');
                wp_enqueue_script('countdown-min');
                wp_enqueue_script('woolentor-flash-sale-module');
            },
            'location' => BLOCKS_PATH,
        ];

        return $block_list;
    }

}