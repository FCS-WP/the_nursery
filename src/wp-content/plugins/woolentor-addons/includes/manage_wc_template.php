<?php
/**
*  Manage WC Template
*/
class Woolentor_Manage_WC_Template{

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){
        add_action( 'init', [ $this, 'init' ] );
    }

    public function init(){

        // Body classes
        add_filter( 'body_class', [ $this, 'body_classes' ] );

        // Add Admin bar Menu
        add_action( 'admin_bar_menu', [ $this, 'add_menu_in_admin_bar' ], 300 );

        // Change Template
        add_filter( 'template_include', [ $this, 'change_page_template' ], 999);
        
        // Product details page
        add_action( 'woolentor_woocommerce_product_content', [ $this, 'set_product_page_builder_content' ], 5 );

        // Product Archive Page
        add_action( 'woolentor_woocommerce_archive_product_content', [ $this, 'set_shop_page_builder_content' ] );

    }

    /**
     * [body_classes]
     * @param  [array] $classes
     * @return [array] 
     */
    public function body_classes( $classes ){

        $class_prefix = 'elementor-page-';

        if ( is_product() && false !== self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {

            $classes[] = $class_prefix.self::has_template( 'singleproductpage', '_selectproduct_layout' );

        }elseif ( (is_checkout() && false !== self::has_template( 'productcheckoutpage' ) && !is_wc_endpoint_url('order-received') && !is_checkout_pay_page()) || (isset($_REQUEST['wc-ajax']) &&  $_REQUEST['wc-ajax'] == 'update_order_review') ){

            $classes[] = $class_prefix.self::has_template( 'productcheckoutpage' );

        }elseif( is_checkout() && is_wc_endpoint_url('order-received') && false !== self::has_template( 'productthankyoupage' ) ){

            $classes[] = $class_prefix.self::has_template( 'productthankyoupage' );

        }elseif( is_shop() && false !== self::has_template( 'productarchivepage' ) ){

            $classes[] = $class_prefix.self::has_template( 'productarchivepage' );

        }elseif ( is_account_page() ) {
            if ( is_user_logged_in() && false !== self::has_template( 'productmyaccountpage' ) ) {
                $classes[] = $class_prefix.self::has_template( 'productmyaccountpage' );
            }else{
                if( false !== self::has_template( 'productmyaccountloginpage' ) ){
                    $classes[] = $class_prefix.self::has_template( 'productmyaccountloginpage' );
                }
            }
        }else{
            if ( is_cart() && ! WC()->cart->is_empty() && false !== self::has_template( 'productcartpage' ) ) {
                $classes[] = $class_prefix.self::has_template( 'productcartpage' );
            }else{
                if( false !== self::has_template( 'productemptycartpage' ) ){
                    $classes[] = $class_prefix.self::has_template( 'productemptycartpage' );
                }
                if( WC()->cart && WC()->cart->is_empty() ){
                    $classes[] = 'woolentor-empty-cart';
                }
            }
        }

        return $classes;

    }

    /**
     * [add_menu_in_admin_bar] Add Admin Bar Menu For Navigate Quick Edit builder template
     *
     * @param \WP_Admin_Bar $wp_admin_bar
     * @return void
     */
    public function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {

        if( function_exists('woolentorBlocks_get_ID') ){
            if( ! Woolentor_Template_Manager::instance()->edit_with_gutenberg( woolentorBlocks_get_ID() ) || is_admin()){
                return;
            }

            $icon = WOOLENTOR_ADDONS_PL_URL.'includes/admin/assets/images/icons/menu-bar_32x32.png';

            $wp_admin_bar->add_menu( [
                'id'     => 'woolentor_template_builder',
                'parent' => '',
                'title'  => sprintf( '<img src="%s" alt="%s">%s', $icon, __('WooLentor Admin Menu','woolentor'), esc_html__('WooLentor','woolentor') ),
            ] );

            $wp_admin_bar->add_menu( [
                'id'     => 'woolentor_template_' . woolentorBlocks_get_ID() . get_the_ID(),
                'parent' => 'woolentor_template_builder',
                'href'   => get_edit_post_link( woolentorBlocks_get_ID() ),
                'title'  => sprintf( '%s', get_the_title( woolentorBlocks_get_ID() ) ),
                'meta' => [],
            ] );
        }

    }

    /**
     * [has_template]
     * @param  [string]  $field_key
     * @return boolean | int
     */
    public static function has_template( $field_key = '', $meta_key = '' ){
        $template_id    = Woolentor_Template_Manager::instance()->get_template_id( $field_key );
        $wlindividualid = !empty( $meta_key ) && get_post_meta( get_the_ID(), $meta_key, true ) ? get_post_meta( get_the_ID(), $meta_key, true ) : '0';

        if( '0' !== $wlindividualid ){
            return $wlindividualid;
        }elseif( '0' !== $template_id ){
            return $template_id;
        }else{
            return false;
        }

    }

    /**
     * [get_template_id]
     * @param  [string]  $field_key
     * @param  [string]  $meta_key
     * @return boolean | int
     */
    public static function get_template_id( $field_key = '', $meta_key = '' ){
        $wltemplateid = Woolentor_Template_Manager::instance()->get_template_id( $field_key );
        $wlindividualid = !empty( $meta_key ) && get_post_meta( get_the_ID(), $meta_key, true ) ? get_post_meta( get_the_ID(), $meta_key, true ) : '0';

        if( $wlindividualid != '0' ){ 
            $wltemplateid = $wlindividualid; 
        }
        return $wltemplateid;
    }

    /**
     * [render_build_content]
     * @param  [int]  $id
     * @return string
     */
    public static function render_build_content( $id, $inline_css = false ){

        $output = '';
        $document = woolentor_is_elementor_editor() ? Elementor\Plugin::instance()->documents->get( $id ) : false;

        if( $document && $document->is_built_with_elementor() ){
            $output = Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
        }else{
            $content = get_the_content( null, false, $id );

            if ( has_blocks( $content ) ) {
                if( method_exists('\WooLentorBlocks\Manage_Styles','generate_inline_css') && $inline_css === true ){
                    \WooLentorBlocks\Manage_Styles::instance()->generate_inline_css( $id );
                }
                $blocks = parse_blocks( $content );
                $embed = new WP_Embed();
                foreach ( $blocks as $block ) {
                    $output .= $embed->autoembed(do_shortcode( render_block( $block ) )); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                }
            }else{
                $content = apply_filters( 'the_content', $content );
                $content = str_replace(']]>', ']]&gt;', $content );
                return $content;
            }

        }

        return $output;

    }

    /*
    * Manage Product Page
    */

    // Set Builder Content For Shop page
    public function set_shop_page_builder_content(){
        $archive_template_id = $this->archive_template_id();
        if( $archive_template_id != '0' ){
            echo self::render_build_content( $archive_template_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    // Set Builder content for Single product page
    public static function set_product_page_builder_content() {
        if ( self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {
            $wltemplateid = self::get_template_id( 'singleproductpage', '_selectproduct_layout' );
            echo self::render_build_content( $wltemplateid ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    /*
    * Manage product archive page
    */
    public function archive_template_id(){
        $template_id = 0;

        if ( defined('WOOCOMMERCE_VERSION') ) {

            $termobj            = get_queried_object();
            $get_all_taxonomies = woolentor_get_taxonomies();

            if ( is_shop() || ( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() ) || ( isset( $termobj->taxonomy ) && is_tax( $termobj->taxonomy ) && array_key_exists( $termobj->taxonomy, $get_all_taxonomies ) ) ) {
                
                $product_shop_custom_page_id = self::get_template_id( 'productarchivepage' );

                // Archive Layout Control
                $wltermlayoutid = 0;
                if(( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() )){

                    $product_archive_custom_page_id = self::get_template_id( 'productallarchivepage' );
                    $product_display_mode = function_exists('woocommerce_get_loop_display_mode') ? woocommerce_get_loop_display_mode() : '';

                    // Get Meta Value
                    $wltermlayoutid = get_term_meta( $termobj->term_id, 'wooletor_selectcategory_layout', true ) ? get_term_meta( $termobj->term_id, 'wooletor_selectcategory_layout', true ) : '0';

                    if( !empty( $product_archive_custom_page_id ) && $wltermlayoutid == '0' ){
                        if ( 'subcategories' === $product_display_mode || 'both' === $product_display_mode ) {
                            $wltermlayoutid = 0;
                        }else{
                            $wltermlayoutid = $product_archive_custom_page_id;
                        }
                    }

                }
                if( $wltermlayoutid != '0' ){ 
                    $template_id = $wltermlayoutid;
                }else{
                    if ( !empty( $product_shop_custom_page_id ) ) {
                        $template_id = $product_shop_custom_page_id;
                    }
                }

            }

            return $template_id;
        }

    }

    /**
     * Page Tempalte
     */
    public function get_page_template_path( $template_part, $template_id ) {

        $template = 0;

        if( Woolentor_Template_Manager::instance()->edit_with_gutenberg( $template_id ) ) {

            $page_template_slug = get_post_meta( $template_id, '_wp_page_template', true );

            $page_template_slug = ( in_array( $page_template_slug, ['elementor_header_footer', 'elementor_canvas'] ) ? 'woolentor_fullwidth' : $page_template_slug );
			$template = !empty( $page_template_slug ) ? WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/page/'.$page_template_slug.'.php' : $template;

			// if( empty( $page_template_slug ) ) {
			// 	$template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/page/woolentor-default.php';
			// }

            if( empty( $page_template_slug ) ) {
				$template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/page/default.php';
			}

            add_action('woolentor/builder/content', function () use ( $template_id, $template_part ) {
                include_once ( $this->get_template_part( $template_part, $template_id ) );
            });

            return $template;
        }

        if( woolentor_is_elementor_active() ){

            // The code snippet originates in Elementor, specifically in /elementor/modules/page-templates/module.php at line 82.
            $document        = \Elementor\Plugin::$instance->documents->get_doc_for_frontend($template_id);
            $template_module = \Elementor\Plugin::$instance->modules_manager->get_modules('page-templates');
            $template_path = 0;

            if( $document && $document::get_property('support_wp_page_templates') ) {
                $page_template = $document->get_meta('_wp_page_template');
                $page_template = ( in_array( $page_template, ['elementor_header_footer', 'elementor_canvas'] ) ? $page_template : '');

                $template_path = $template_module->get_template_path( $page_template );

                if( 'elementor_theme' !== $page_template && !$template_path && $document->is_built_with_elementor() ) {
                    $kit_default_template = \Elementor\Plugin::$instance->kits_manager->get_current_settings('default_page_template');
                    $template_path        = $template_module->get_template_path( $kit_default_template );
                }

                if( $template_path ) {
                    $template = $template_path;
                }
            }

            if( $template_path ) {
                $template_module->set_print_callback(function () use ( $template_id, $template_part ){
                    include_once ( $this->get_template_part( $template_part, $template_id ) );
                });
            } else{
                if( !$this->is_elementor_editor_mode() ){
                    $template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/page/default.php';
                    add_action('woolentor/builder/content', function () use ( $template_id, $template_part ) {
                        include_once ( $this->get_template_part( $template_part, $template_id ) );
                    });
                }
            }

        }

        return $template;
    }
    
    // Manage Template part
    public function get_template_part( $slug, $template_id ){
        if( empty( $template_id ) ){
            return;
        }
        $template = '';
        if( $slug === 'shop'){
            $template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/content-shop.php';
        }
        elseif( $slug === 'singleproduct' ){
            if ( self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {
                global $product, $post;
                if ( $product && !is_a( $product, 'WC_Product' ) ) {
                    $product = wc_get_product( $post->ID );
                }
                $template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/single-product.php';
            }
        }

        return $template;
    }

    /**
     * Template Change
     *
     * @param [type] $template
     * @return void
     */
    public function change_page_template( $template ){

        $template_part = '';
        $template_id = 0;

        if ( class_exists( 'WooCommerce' ) ) {

            if ( is_singular( 'product' ) ) {
                if ( self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {
                    $single_product_page_id = self::get_template_id( 'singleproductpage', '_selectproduct_layout' );
                    if( !empty( $single_product_page_id ) ) {
                        $template_id = $single_product_page_id;
                        $template_part = 'singleproduct';
                    }
                }
            }else{
                $archive_template_id = $this->archive_template_id();
                if( !empty( $archive_template_id )){
                    $template_id = $archive_template_id;
                    $template_part = 'shop';
                }
            }

        }

        if( !empty( $template_id ) ){
            $template_path = $this->get_page_template_path( $template_part, $template_id );
            if ( $template_path && !$this->is_elementor_editor_mode()) {
                $template = $template_path;
            }
            add_filter('woolentor_builder_template_width',function( $template_width ) use( $template_id ){
                $template_width = $this->get_template_width( $template_id );
                return $template_width;
            });
        }

        return $template;
    }

    // Check Elementor Editor mode
    public function is_elementor_editor_mode(){
        if( isset( $_GET['action'] ) && $_GET['action'] == 'elementor' ){
            return true;
        }else{
            if( isset( $_GET['elementor-preview'] ) && $_GET['elementor-preview'] !== ''){
                return true;
            }
            return false;
        }
    }

    // Get Template width
    public static function get_template_width( $template_id ){
        if( ! Woolentor_Template_Manager::instance()->edit_with_gutenberg( $template_id ) ){
            return '';
        }
        $get_width = get_post_meta( $template_id, '_woolentor_container_width', true );
		return $get_width ? $get_width : '';
    }

    // Get Builder Template ID
    public function get_builder_template_id(){

        if ( is_singular( 'product' ) ) {
            if ( self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {
                $template_id = self::get_template_id( 'singleproductpage', '_selectproduct_layout' );
            }else{
                $template_id = '';
            }
        }else{
            $archive_template_id = $this->archive_template_id();
            $template_id         = $archive_template_id != '0' ? $archive_template_id : '';
        }

        return apply_filters( 'woolentor_builder_template_id', $template_id );

    }

}

Woolentor_Manage_WC_Template::instance();