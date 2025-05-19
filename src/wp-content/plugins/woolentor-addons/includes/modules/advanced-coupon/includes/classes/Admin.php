<?php
namespace Woolentor\Modules\AdvancedCoupon;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Admin handlers class
 */
class Admin {
    use Singleton;
    
    /**
     * Initialize the class
     */
    private function __construct() {
        $this->includes();
        $this->init();
    }

    /**
     * Load Required files
     *
     * @return void
     */
    private function includes(){
        require_once( __DIR__. '/Admin/Fields.php' );
        if( ENABLED ){
            require_once(__DIR__.'/Admin/Coupon_Meta_Boxes.php');
        }
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function init(){
        Admin\Fields::instance();
        if( ENABLED ){
            add_action('current_screen', function ($screen) {
                if ( $screen->post_type === 'shop_coupon' ) {
                    add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
                    Admin\Coupon_Meta_Boxes::instance();
                }
            });
        }
    }

    /**
     * Admin Scripts
     * @return void
     */
    public function admin_scripts(){
        wp_enqueue_style('woolentor-advanced-coupon', MODULE_ASSETS . '/css/admin.css');
        wp_enqueue_script('woolentor-advanced-coupon', MODULE_ASSETS . '/js/admin.js');
        
        global $pagenow;
        $admin_local_obj = [
            'bulk_generate_btn_text' => esc_html__( 'Bulk Coupon Generate', 'woolentor' ),
            'back_btn_text'          => esc_html__( 'Back', 'woolentor' ),
            'bulk_title'             => esc_html__( 'Generate Bulk Coupon', 'woolentor' ),
            'single_title'           => esc_html__( 'Add new coupon', 'woolentor' ),
        ];

        if( $this->bulk_generate_button($pagenow) ){
            $admin_local_obj['bulk_generate_button'] = sprintf( '<a href="%1$s" class="page-title-action '.( $pagenow === 'post-new.php' ? 'woolentor-bulk-coupon-btn' : '' ).'">%2$s</a>', admin_url( 'post-new.php?post_type=shop_coupon&wlgeneratebulk=yes' ), esc_html( $admin_local_obj['bulk_generate_btn_text'] ) );
        }else{
            if( $pagenow === 'post-new.php' ){
                $admin_local_obj['bulk_generate_button'] = sprintf( '<a href="%1$s" class="page-title-action '.( $pagenow === 'post-new.php' ? 'woolentor-bulk-coupon-btn back-to-default' : '' ).'">%2$s</a>', admin_url( 'edit.php?post_type=shop_coupon' ), esc_html( $admin_local_obj['back_btn_text'] ) );
            }
        }

        wp_localize_script( 'woolentor-advanced-coupon', 'woolentor_advanced_local_obj', $admin_local_obj );
    }

    /**
     * Bulk Coupon Generate Button Condition manage.
     * @param mixed $pagenow
     */
    public function bulk_generate_button($pagenow){
        $bulk_enable = isset( $_GET['wlgeneratebulk'] ) ? $_GET['wlgeneratebulk'] : 'no';

        if( $bulk_enable === 'no' && $pagenow !== 'post.php' ){
            return true;
        }
    }

}