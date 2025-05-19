<?php
namespace Woolentor\Modules\Badges\Frontend;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Badge_Manager {
    use Singleton;

    public function __construct(){

        add_action( 'woocommerce_before_shop_loop_item_title', [$this, 'template_loop_product_thumbnail_before'], 9 );
        add_action( 'woocommerce_before_shop_loop_item_title', [$this, 'template_loop_product_thumbnail_after'], 11 );

        // Shop / Archive
        add_filter( 'woocommerce_product_get_image', [$this, 'product_badge_loop_item'] , 100000, 2 );

        // Single Product page
        add_action('woolentor_product_thumbnail_image', [$this, 'product_badge_single_product'], 10);
        add_action('woocommerce_product_thumbnails', [$this, 'product_badge_single_product'], 1000);

    }

    /**
     * Add Wrapper For Product badge
     * @return void
     */
    public function template_loop_product_thumbnail_before(){
        echo "<div class='woolentor-product-badge-main-area'>";
    }
    
    /**
     * Wrapper End
     * @return void
     */
    public function template_loop_product_thumbnail_after(){
        echo "</div>";
    }

    /**
     * Check Is Excluded Product
     * @param mixed $product_id
     * @param mixed $badge
     * @return bool
     */
    private function is_excluded($product_id, $badge) {
        $exclude_product_ids = !empty($badge['exclude_products']) ? $badge['exclude_products'] : [];
        if( woolentor_is_pro() ){
            return in_array($product_id, $exclude_product_ids);
        }else{
            return false;
        }
    }

    /**
     * Get Bagdes List
     * @return array
     */
    private function badges_list(){
        $badges_list = woolentor_get_option( 'badges_list', 'woolentor_badges_settings', [] );
        if( !empty( $badges_list ) && is_array( $badges_list )){
            if( !woolentor_is_pro() ){
                return isset( $badges_list[1] ) ? [ $badges_list[0], $badges_list[1] ] : [ $badges_list[0] ];
            }
            return $badges_list;
        }
        return [];
    }
    
    /**
     * Manage Selected Product Condition
     * @param mixed $badge
     * @param mixed $product_id
     * @return void
     */
    private function handle_selected_product_condition($badge, $product_id) {
        if (!empty($badge['products']) && in_array($product_id, $badge['products'])) {
            $this->badge_html($badge);
        }
    }

    /**
     * Manage Category Condition
     * @param mixed $badge
     * @param mixed $product
     * @return void
     */
    private function handle_category_condition($badge, $product) {
        $category_list = !empty($badge['categories']) ? $badge['categories'] : [];
        $product_categories = $product->get_category_ids();
        $match_category = array_intersect($product_categories, $category_list);
    
        if (!empty($category_list) && !empty($match_category)) {
            $this->badge_html($badge);
        }
    }
    
    /**
     * Manage Out Of Stock condition
     * @param mixed $product
     * @param mixed $badge
     * @return void
     */
    private function handle_out_of_product_condition($product, $badge) {
        if (!$product->managing_stock() && !$product->is_in_stock()) {
            $this->badge_html($badge);
        }
    }

    /**
     * Product badge HTML
     * @param mixed $badge
     * @return void
     */
    public function badge_html( $badge ){

        $badge_position = !empty( $badge['badge_position'] ) ? $badge['badge_position'] : "";

        $classes = [
            'woolentor-product-badge-position-'.$badge_position,
            'woolentor-product-badge-type-'.$badge['badge_type'],
        ];

        // If Badge Set From Product Screen
        if( (!empty( $badge['has_ind'] ) && $badge['has_ind'] === true) ){
            $classes[] = 'has_individual_badge';
        }

        $atts = [
            'template_name' => $badge['badge_type'],
            'badge'         => $badge,
            'classes'       => implode(' ', $classes ),
        ];

        $badge_attr = apply_filters( 'woolentor_badge_arg', $atts );
        woolentor_get_template( 'badge-'.$atts['template_name'], $badge_attr, true, \Woolentor\Modules\Badges\TEMPLATE_PATH );

    }

    /**
     * Product Badges Manager
     */
    public function product_badges(){
        ob_start();

        $badges_list = $this->badges_list();

        if( empty($badges_list) || !is_array( $badges_list ) ){
            return;
        }

        foreach( $badges_list as $key => $badge ){
            $this->render_product_badge($badge);
        }

        $output = ob_get_clean();
        return $output;
    }

    /**
     * Product Page Render Logic
     * @param mixed $badge
     * @return void
     */
    public function render_product_badge( $badge ){
        global $product;
    
        // Validate product and check if it's an instance of WC_Product.
        if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
            return;
        }
    
        $product_id = $product->get_id();
        $ind_custom_sale_text = get_post_meta( $product_id, '_saleflash_text', true );
    
        // Check if product is excluded and Not set from Individual Product screen.
        if ( $this->is_excluded($product_id, $badge) && empty( $ind_custom_sale_text ) ) {
            return;
        }

        // If Badge Assign from Product Edit Screen.
        if( !empty( $ind_custom_sale_text ) ){
            $badge['badge_type'] = 'text';
            $badge['badge_text'] = $ind_custom_sale_text;
            $badge['has_ind'] = true;
        }
    
        switch ( $badge['badge_condition'] ) {
            case 'all_product':
                $this->badge_html($badge);
                break;
    
            case 'selected_product':
                $this->handle_selected_product_condition($badge, $product_id);
                break;
    
            case 'category':
                $this->handle_category_condition($badge, $product);
                break;
    
            case 'on_sale':
                if ( $product->is_on_sale() ) {
                    $this->badge_html($badge);
                }
                break;
    
            default:
                $this->handle_out_of_product_condition($product, $badge);
                break;
        }
    }

    /**
     * Manage Product Badge for Archive / Shop page
     * @param mixed $product_image
     * @param mixed $product
     * @return mixed
     */
    public function product_badge_loop_item( $product_image, $product = false ){
        $termobj            = get_queried_object();
        $get_all_taxonomies = woolentor_get_taxonomies();
        $is_archive_page = ( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() ) || ( isset( $termobj->taxonomy ) && is_tax( $termobj->taxonomy ) && array_key_exists( $termobj->taxonomy, $get_all_taxonomies ) );

        if ( !is_admin() && (is_shop() || is_single() || $is_archive_page) ){
            return $product_image.$this->product_badges();
        }else{
            return $product_image;
        }
    }

    /**
     * Product Badge For Single Product page
     * @return void
     */
    public function product_badge_single_product(){
        echo $this->product_badges();
    }

}