<?php
namespace Woolentor\Modules\RenameLabel\Frontend;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Label_Manager {
    use Singleton;

    public function __construct(){

        // Shop / Archive page
        add_filter( 'woocommerce_product_add_to_cart_text', [$this, 'custom_add_cart_button_shop_page'], 99, 2 );

        // Single Product page
        add_filter( 'woocommerce_product_single_add_to_cart_text', [$this, 'custom_add_cart_button_single_product'] );

        // Single Product Description tab label
        add_filter( 'woocommerce_product_description_tab_title', [$this, 'rename_description_product_tab_label'] );

        // Single Product Description Heading
        add_filter( 'woocommerce_product_description_heading', [$this, 'rename_description_tab_heading'] );

        //Single Product Additional tab label
        add_filter( 'woocommerce_product_additional_information_tab_title', [$this, 'rename_additional_information_product_tab_label'] );

        // Single Product Additional Information Heading
        add_filter( 'woocommerce_product_additional_information_heading', [$this, 'rename_additional_information_tab_heading'] );

        // Single Product Review tab label
        add_filter( 'woocommerce_product_reviews_tab_title', [$this, 'rename_reviews_product_tab_label'] );

        // Checkout page place order Button text
        add_filter( 'woocommerce_order_button_text', [$this, 'rename_place_order_button'] );

    }

    /**
     * Shop Page Add to cart Text
     * @param mixed $label
     * @return string
     */
    public function custom_add_cart_button_shop_page( $label ){
        return __( woolentor_get_option_label_text( 'wl_shop_add_to_cart_txt', 'woolentor_rename_label_tabs', 'Add to Cart' ), 'woolentor' );
    }

    /**
     * Single Product add to cart text
     * @param mixed $label
     * @return string
     */
    public function custom_add_cart_button_single_product( $label ){
        return __( woolentor_get_option_label_text( 'wl_add_to_cart_txt', 'woolentor_rename_label_tabs', 'Add to Cart' ), 'woolentor' );
    }

    /**
     * Single Product page descrription product tab label
     * @return string
     */
    public function rename_description_product_tab_label() {
        return __( woolentor_get_option_label_text( 'wl_description_tab_menu_title', 'woolentor_rename_label_tabs', 'Description' ), 'woolentor' );
    }

    /**
     * Single Product descriptio tab Heading
     * @return string
     */
    public function rename_description_tab_heading() {
        return __( woolentor_get_option_label_text( 'wl_description_tab_menu_title', 'woolentor_rename_label_tabs', 'Description' ), 'woolentor' );
    }

    /**
     * Single Product additional tab label
     * @return string
     */
    public function rename_additional_information_product_tab_label() {
        return __( woolentor_get_option_label_text( 'wl_additional_information_tab_menu_title', 'woolentor_rename_label_tabs','Additional Information' ), 'woolentor' );
    }

    /**
     * Single Product additional tab heading
     * @return string
     */
    public function rename_additional_information_tab_heading() {
        return __( woolentor_get_option_label_text( 'wl_additional_information_tab_menu_title', 'woolentor_rename_label_tabs','Additional Information' ), 'woolentor' );
    }

    /**
     * Single Product Reviews Product tab label
     * @return string
     */
    public function rename_reviews_product_tab_label() {
        return __( woolentor_get_option_label_text( 'wl_reviews_tab_menu_title', 'woolentor_rename_label_tabs','Reviews' ), 'woolentor');
    }

    /**
     * Place order Button Text
     * @return string
     */
    public function rename_place_order_button() {
        return __( woolentor_get_option_label_text( 'wl_checkout_placeorder_btn_txt', 'woolentor_rename_label_tabs','Place order' ), 'woolentor');
    }
    

}