<?php
namespace WoolentorOptions\Admin;

class Options_Field {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Admin]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function get_settings_tabs(){
        $tabs = array(
            'welcome' => [
                'id'    => 'woolentor_welcome_tabs',
                'title' => esc_html__( 'Welcome', 'woolentor' ),
                'icon' => 'dashicons dashicons-admin-home',
                'show_in_nav' => true,
                'content' => [
                    'header' => false,
                    'footer' => false,
                ]
            ],
            'woo_template' => [
                'id'    => 'woolentor_woo_template_tabs',
                'title' => esc_html__( 'WooCommerce Template', 'woolentor' ),
                'icon'  => 'wli wli-store',
                'show_in_nav' => true,
                'content' => [
                    'header' => false,
                    'column' => 1,
                    'title' => __( 'Your Widget List', 'woolentor' ),
                    'desc'  => __( 'Freely use these elements to create your site. You can enable which you are not using, and, all associated assets will be disable to improve your site loading speed.', 'woolentor' ),
                ]
            ],
            'gutenberg' => [
                'id'    => 'woolentor_gutenberg_tabs',
                'title' => esc_html__( 'Gutenberg', 'woolentor' ),
                'icon'  => 'wli wli-cog',
                'show_in_nav' => true,
                'content' => [
                    'header' => true,
                    'column' => 4,
                    'title' => __( 'Your Widget List', 'woolentor' ),
                    'desc'  => __( 'Freely use these elements to create your site. You can enable which you are not using, and, all associated assets will be disable to improve your site loading speed.', 'woolentor' ),
                ],
                'save_under_parent' => true,
                'sections' => [
                    'blocks'=>[
                        'id'    => 'woolentor_gutenberg_blocks_tabs',
                        'title' => esc_html__( 'Blocks', 'woolentor' ),
                        'icon'  => 'wli-store',
                        'content' => [
                            'header' => true,
                            'column' => 4,
                            'title' => __( 'ShopLentor Blocks', 'woolentor' ),
                            'desc'  => __( 'You can enable or disable all blocks by one click.', 'woolentor' ),
                        ]
                    ],
                    'settings'=>[
                        'id'    => 'woolentor_gutenberg_settings_tabs',
                        'title' => esc_html__( 'Settings', 'woolentor' ),
                        'icon'  => 'dashicons-admin-home',
                        'content' => [
                            'header' => false,
                            'column' => 1,
                            'title' => __( 'Gutenberg Settings', 'woolentor' ),
                            'desc'  => __( 'Gutenberg Settings', 'woolentor' ),
                        ]
                    ]
                ]
            ],
            'elements' => [
                'id'    => 'woolentor_elements_tabs',
                'title' => __( 'Elements', 'woolentor' ),
                'icon'  => 'wli wli-images',
                'show_in_nav' => true,
                'content' => [
                    'header' => true,
                    'column' => 3,
                    'title' => __( 'ShopLentor Element', 'woolentor' ),
                    'desc'  => __( 'You can enable or disable all options by one click.', 'woolentor' ),
                ],
            ],
            'modules' => [
                'id'    => 'woolentor_others_tabs',
                'title' => esc_html__( 'Modules', 'woolentor' ),
                'icon'  => 'wli wli-grid',
                'show_in_nav' => true,
                'content' => [
                    'header' => true,
                    'column' => 3,
                    'title' => __( 'ShopLentor Module', 'woolentor' ),
                    'desc'  => __( 'You can enable or disable all options by one click.', 'woolentor' ),
                ],
            ],
            'style' => [
                'id'    => 'woolentor_style_tabs',
                'title' => __( 'Style', 'woolentor' ),
                'icon'  => 'wli wli-tag',
                'show_in_nav' => false,
                'content' => [
                    'header' => false,
                    'column' => 1,
                    'title' => __( 'Style Tabs', 'woolentor' ),
                    'desc'  => __( 'Style Settings for Universal Product Layput', 'woolentor' ),
                ],
            ],

            'extension' => [
                'id'    => 'woolentor_extension_tabs',
                'title' => esc_html__( 'Extensions', 'woolentor' ),
                'icon'  => 'wli wli-masonry',
                'show_in_nav' => true,
                'content' => [
                    'header' => false,
                    'footer' => false,
                ],

                'sections' => [
                    'free'=>[
                        'id'    => 'woolentor_free_extension_tabs',
                        'title' => esc_html__( 'Free Extension', 'woolentor' ),
                        'icon'  => 'wli-store',
                        'content' => [
                            'header' => false,
                            'footer' => false,
                        ]
                    ],
                    'pro'=>[
                        'id'    => 'woolentor_pro_extension_tabs',
                        'title' => esc_html__( 'Pro Extension', 'woolentor' ),
                        'icon'  => 'dashicons-admin-home',
                        'content' => [
                            'header' => false,
                            'footer' => false,
                        ]
                    ]
                ]

            ],

            'freevspro' => [
                'id'    => 'woolentor_freevspro_tabs',
                'title' => esc_html__( 'Free VS Pro', 'woolentor' ),
                'icon' => 'dashicons dashicons-tickets-alt',
                'show_in_nav' => true,
                'content' => [
                    'header' => false,
                    'footer' => false,
                ]
            ]

        );

        return apply_filters( 'woolentor_admin_field_tabs', $tabs );

    }

    public function get_registered_settings(){
        $settings = array(

            'woolentor_welcome_tabs' => array(
                array(
                    'id'   => 'welcome_content',
                    'type' => 'html',
                    'html' => esc_html__('Loading...','woolentor'),
                    'component' => 'WelcomeComponent'
                )
            ),

            'woolentor_woo_template_tabs' => array(
                array(
                    'id'    => 'enablecustomlayout',
                    'name'   => esc_html__( 'Enable / Disable Template Builder', 'woolentor' ),
                    'desc'    => esc_html__( 'You can enable/disable template builder from here.', 'woolentor' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'shoppageproductlimit',
                    'name' => esc_html__( 'Product Limit', 'woolentor' ),
                    'desc'  => esc_html__( 'You can handle the product limit for the Shop page', 'woolentor' ),
                    'min'               => 1,
                    'max'               => 100,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '2',
                    'sanitize_callback' => 'floatval',
                    'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ]
                ),

                array(
                    'id'    => 'singleproductpage',
                    'name'   => esc_html__( 'Single Product Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a custom template for the product details page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                    'options' => [
                        'group'=>[
                            'woolentor' => [
                                'label' => __( 'WooLentor', 'woolentor' ),
                                'options' => woolentor_wltemplate_list( array('single') )
                            ],
                            'elementor' => [
                                'label' => __( 'Elementor', 'woolentor' ),
                                'options' => woolentor_elementor_template()
                            ]
                        ]
                    ],
                   'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ]
                ),

                array(
                    'id'    => 'productarchivepage',
                    'name'   => esc_html__( 'Product Shop Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a custom template for the Shop page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                    'options' => [
                        'group'=>[
                            'woolentor' => [
                                'label' => __( 'WooLentor', 'woolentor' ),
                                'options' => woolentor_wltemplate_list( array('shop','archive') )
                            ],
                            'elementor' => [
                                'label' => __( 'Elementor', 'woolentor' ),
                                'options' => woolentor_elementor_template()
                            ]
                        ]
                    ],
                   'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ]
                ),

                array(
                    'id'    => 'productallarchivepage',
                    'name'   => esc_html__( 'Product Archive Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a custom template for the Product Archive page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                    'options' => [
                        'group'=>[
                            'woolentor' => [
                                'label' => __( 'WooLentor', 'woolentor' ),
                                'options' => woolentor_wltemplate_list( array('shop','archive') )
                            ],
                            'elementor' => [
                                'label' => __( 'Elementor', 'woolentor' ),
                                'options' => woolentor_elementor_template()
                            ]
                        ]
                    ],
                   'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ]
                ),

                array(
                    'id'    => 'productcartpagep',
                    'name'   => esc_html__( 'Cart Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a template for the Cart page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                    'options' => array(
                        '0' => esc_html__('Select a template for the cart page layout','woolentor'),
                        'none' => esc_html__('None','woolentor'),
                    ),
                   'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ],
                    'is_pro'  => true,
                ),

                array(
                    'id'    => 'productcheckoutpagep',
                    'name'   => esc_html__( 'Checkout Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a template for the Checkout page layout', 'woolentor' ),
                    'type'    => 'select',
                    'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                    'default' => '0',
                    'options' => array(
                        '0' => esc_html__('Select a template for the Checkout page layout','woolentor'),
                        'none' => esc_html__('None','woolentor'),
                    ),
                   'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ],
                    'is_pro'  => true,
                ),

                array(
                    'id'    => 'productthankyoupagep',
                    'name'   => esc_html__( 'Thank You Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'Select a template for the Thank you page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                    'options' => array(
                        '0' => esc_html__('Select a template for the Thank you page layout','woolentor'),
                        'none' => esc_html__('None','woolentor'),
                    ),
                   'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ],
                    'is_pro'    => true,
                ),

                array(
                    'id'    => 'productmyaccountpagep',
                    'name'   => esc_html__( 'My Account Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'Select a template for the My Account page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                    'options' => array(
                        '0' => esc_html__('Select a template for the My account page layout','woolentor'),
                        'none' => esc_html__('None','woolentor'),
                    ),
                   'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ],
                    'is_pro'  => true,
                ),

                array(
                    'id'    => 'productmyaccountloginpagep',
                    'name'   => esc_html__( 'My Account Login page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'Select a template for the Login page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                    'options' => array(
                        '0' => esc_html__('Select a template for the My account login page layout','woolentor'),
                        'none' => esc_html__('None','woolentor'),
                    ),
                   'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ],
                    'is_pro'  => true,
                ),

                array(
                    'id'    => 'productquickviewp',
                    'name'   => esc_html__( 'Quick View Template', 'woolentor' ),
                    'desc'    => esc_html__( 'Select a template for the product\'s quick view layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                    'options' => array(
                        '0' => esc_html__('Select a template for the Quick view layout','woolentor'),
                        'none' => esc_html__('None','woolentor'),
                    ),
                   'condition' => [
                        'key'=>'enablecustomlayout',
                        'operator'=>'==',
                        'value' => 'on'
                    ],
                    'is_pro'  => true,
                ),

            ),

            'woolentor_gutenberg_tabs' => array(

                array(
                    'id'    => 'css_add_via',
                    'name'   => esc_html__( 'Add CSS through', 'woolentor' ),
                    'desc'    => esc_html__( 'Choose how you want to add the newly generated CSS.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'internal',
                    'options' => array(
                        'internal' => esc_html__('Internal','woolentor'),
                        'external' => esc_html__('External','woolentor'),
                    ),
                    'group' => 'settings',
                ),

                array(
                    'id'  => 'container_width',
                    'name' => esc_html__( 'Container Width', 'woolentor' ),
                    'desc'  => esc_html__( 'You can set the container width from here.', 'woolentor' ),
                    'min'               => 1,
                    'max'               => 10000,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '1140',
                    'sanitize_callback' => 'floatval',
                    'group' => 'settings',
                ),
                

                array(
                    'id'      => 'general_blocks_heading',
                    'heading'  => esc_html__( 'General', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'id'    => 'product_tab',
                    'name'   => esc_html__( 'Product Tab', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'promo_banner',
                    'name'   => esc_html__( 'Promo Banner', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'special_day_offer',
                    'name'   => esc_html__( 'Special Day Offer', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'image_marker',
                    'name'   => esc_html__( 'Image Marker', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'store_feature',
                    'name'   => esc_html__( 'Store Feature', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'brand_logo',
                    'name'   => esc_html__( 'Brand Logo', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'category_grid',
                    'name'   => esc_html__( 'Category Grid', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'faq',
                    'name'   => esc_html__( 'FAQ', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_curvy',
                    'name'   => esc_html__( 'Product Curvy', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'archive_title',
                    'name'   => esc_html__( 'Archive Title', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'breadcrumbs',
                    'name'   => esc_html__( 'Breadcrumbs', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'recently_viewed_products',
                    'name'   => esc_html__( 'Recently Viewed Products', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_grid',
                    'name'   => esc_html__( 'Product Grid', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'is_pro'  => true,
                ),

                array(
                    'id'    => 'customer_review',
                    'name'   => esc_html__( 'Customer Review', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'is_pro'  => true,
                ),

                array(
                    'id'      => 'shop_blocks_heading',
                    'heading'  => esc_html__( 'Shop / Archive', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'id'    => 'shop_archive_product',
                    'name'   => esc_html__( 'Product Archive (Default)', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'product_filter',
                    'name'   => esc_html__( 'Product Filter', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'product_horizontal_filter',
                    'name'   => esc_html__( 'Product Horizontal Filter', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'archive_result_count',
                    'name'   => esc_html__( 'Archive Result Count', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'archive_catalog_ordering',
                    'name'   => esc_html__( 'Archive Catalog Ordering', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'      => 'single_blocks_heading',
                    'heading'  => esc_html__( 'Single Product', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'id'    => 'product_title',
                    'name'   => esc_html__('Product Title','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_price',
                    'name'   => esc_html__('Product Price','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_addtocart',
                    'name'   => esc_html__('Product Add To Cart','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_short_description',
                    'name'   => esc_html__('Product Short Description','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_description',
                    'name'   => esc_html__('Product Description','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_rating',
                    'name'   => esc_html__('Product Rating','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_image',
                    'name'   => esc_html__('Product Image','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'product_video_gallery',
                    'name'   => esc_html__('Product Video Gallery','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_meta',
                    'name'   => esc_html__('Product Meta','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_additional_info',
                    'name'   => esc_html__('Product Additional Info','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_tabs',
                    'name'   => esc_html__('Product Tabs','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_stock',
                    'name'   => esc_html__('Product Stock','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_qrcode',
                    'name'   => esc_html__('Product QR Code','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_related',
                    'name'   => esc_html__('Product Related','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_upsell',
                    'name'   => esc_html__('Product Upsell','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'id'    => 'product_reviews',
                    'name'   => esc_html__('Product Reviews','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'product_categories',
                    'name'   => esc_html__('Product Categories','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'product_tags',
                    'name'   => esc_html__('Product Tags','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'product_sku',
                    'name'   => esc_html__('Product SKU','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'call_for_price',
                    'name'   => esc_html__('Call for Price','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),
                array(
                    'id'    => 'suggest_price',
                    'name'   => esc_html__('Suggest Price','woolentor'),
                    'type'    => 'element',
                    'default' => 'on',
                ),
                array(
                    'id'    => 'product_social_share',
                    'name'   => esc_html__('Product Social Share','woolentor'),
                    'type'    => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'product_stock_progressbar',
                    'name'   => esc_html__('Stock Progressbar','woolentor'),
                    'type'    => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'product_sale_schedule',
                    'name'   => esc_html__('Product Sale Schedule','woolentor'),
                    'type'    => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'product_navigation',
                    'name'   => esc_html__('Product Navigation','woolentor'),
                    'type'    => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'product_advance_image',
                    'name'   => esc_html__('Advance Product Image','woolentor'),
                    'type'    => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'product_thumbnails_zoom_image',
                    'name'   => esc_html__('Product Image With Zoom','woolentor'),
                    'type'    => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'id'      => 'cart_blocks_heading',
                    'name'      => esc_html__( 'Cart', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),
                array(
                    'id'    => 'cart_table',
                    'name'  => esc_html__( 'Product Cart Table', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'cart_table_list',
                    'name'  => esc_html__( 'Product Cart Table (List Style)', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'cart_total',
                    'name'  => esc_html__( 'Product Cart Total', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'corss_sell',
                    'name'  => esc_html__( 'Product Cross Sell', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'return_to_shop',
                    'name'  => esc_html__( 'Return To Shop Button', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'cart_empty_message',
                    'name'  => esc_html__( 'Empty Cart Message', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'id'      => 'checkout_blocks_heading',
                    'heading'  => esc_html__( 'Checkout', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),
                array(
                    'id'    => 'checkout_billing_form',
                    'name'  => esc_html__( 'Checkout Billing Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'checkout_shipping_form',
                    'name'  => esc_html__( 'Checkout Shipping Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'checkout_additional_form',
                    'name'  => esc_html__( 'Checkout Additional..', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'checkout_coupon_form',
                    'name'  => esc_html__( 'Checkout Coupon Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'checkout_payment',
                    'name'  => esc_html__( 'Checkout Payment Method', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'checkout_shipping_method',
                    'name'  => esc_html__( 'Checkout Shipping Method', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'checkout_order_review',
                    'name'  => esc_html__( 'Checkout Order Review', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'checkout_login_form',
                    'name'  => esc_html__( 'Checkout Login Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'id'        => 'myaccount_blocks_heading',
                    'heading'  => esc_html__( 'My Account', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),
                array(
                    'id'    => 'my_account',
                    'name'  => esc_html__( 'My Account', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_navigation',
                    'name'  => esc_html__( 'My Account Navigation', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_dashboard',
                    'name'  => esc_html__( 'My Account Dashboard', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_download',
                    'name'  => esc_html__( 'My Account Download', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_edit',
                    'name'  => esc_html__( 'My Account Edit', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_address',
                    'name'  => esc_html__( 'My Account Address', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_order',
                    'name'  => esc_html__( 'My Account Order', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_logout',
                    'name'  => esc_html__( 'My Account Logout', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_login_form',
                    'name'  => esc_html__( 'Login Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_registration_form',
                    'name'  => esc_html__( 'Registration Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_lost_password',
                    'name'  => esc_html__( 'Lost Password Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'my_account_reset_password',
                    'name'  => esc_html__( 'Reset Password Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'id'      => 'thankyou_blocks_heading',
                    'heading'  => esc_html__( 'Thank You', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),
                array(
                    'id'    => 'thankyou_order',
                    'name'  => esc_html__( 'Thank You Order', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'thankyou_address_details',
                    'name'  => esc_html__( 'Thank You Address', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'thankyou_order_details',
                    'name'  => esc_html__( 'Thank You Order Details', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'id'      => 'additional_blocks_heading',
                    'heading'  => esc_html__( 'Additional', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                )
            ),

            'woolentor_elements_tabs' => array(
                array(
                    'id'      => 'general_widget_heading',
                    'heading'  => esc_html__( 'General', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),
                array(
                    'id'    => 'product_tabs',
                    'name'   => esc_html__( 'Product Tab', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),
                array(
                    'id'    => 'universal_product',
                    'name' => wp_kses_post( 'Universal Product (<a href="'.esc_url(admin_url( 'admin.php?page=woolentor#/style' )).'">Style Settings</a>)' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'product_curvy',
                    'name'   => esc_html__( 'WL: Product Curvy', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'product_image_accordion',
                    'name'   => esc_html__( 'WL: Product Image Accordion', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'product_accordion',
                    'name'   => esc_html__( 'WL: Product Accordion', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_recently_viewed_products',
                    'name'   => esc_html__( 'Recently Viewed Products', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'add_banner',
                    'name'   => esc_html__( 'Ads Banner', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'special_day_offer',
                    'name'   => esc_html__( 'Special Day Offer', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_customer_review',
                    'name'   => esc_html__( 'Customer Review', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_image_marker',
                    'name'   => esc_html__( 'Image Marker', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_category',
                    'name'   => esc_html__( 'Category List', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_category_grid',
                    'name'   => esc_html__( 'Category Grid', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_onepage_slider',
                    'name'   => esc_html__( 'One Page Slider', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_testimonial',
                    'name'   => esc_html__( 'Testimonial', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_store_features',
                    'name'   => esc_html__( 'Store Features', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_faq',
                    'name'   => esc_html__( 'FAQ', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_brand',
                    'name'   => esc_html__( 'Brand Logo', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_product_expanding_gridp',
                    'name'   => esc_html__( 'Product Expanding Grid', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_product_filterable_gridp',
                    'name'   => esc_html__( 'Product Filterable Grid', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_product_pgridp',
                    'name'   => esc_html__( 'Product Grid', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'      => 'archive_widget_heading',
                    'heading'  => esc_html__( 'Shop / Archive', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'id'    => 'wb_archive_product',
                    'name'   => esc_html__( 'Product Archive', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_archive_result_count',
                    'name'   => esc_html__( 'Archive Result Count', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),
    
                array(
                    'id'    => 'wb_archive_catalog_ordering',
                    'name'   => esc_html__( 'Archive Catalog Ordering', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_archive_title',
                    'name'   => esc_html__( 'Archive Title', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_product_filter',
                    'name'   => esc_html__( 'Product Filter', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_product_horizontal_filter',
                    'name'   => esc_html__( 'Product Horizontal Filter', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_advance_product_filterp',
                    'name'   => esc_html__( 'Advanced Product Filter', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_custom_archive_layoutp',
                    'name'   => esc_html__( 'Archive Layout (Custom)', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'      => 'single_widget_heading',
                    'heading'    => esc_html__( 'Single Product', 'woolentor' ),
                    'type'    => 'title',
                    'class'   => 'woolentor_heading_style_two'
                ),

                array(
                    'id'    => 'wb_product_title',
                    'name'   => esc_html__( 'Product Title', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_add_to_cart',
                    'name'   => esc_html__( 'Add to Cart Button', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_breadcrumbs',
                    'name'   => esc_html__( 'Breadcrumbs', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_additional_information',
                    'name'   => esc_html__( 'Additional Information', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_data_tab',
                    'name'   => esc_html__( 'Product Data Tab', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_related',
                    'name'   => esc_html__( 'Related Product', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_description',
                    'name'   => esc_html__( 'Product Description', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_short_description',
                    'name'   => esc_html__( 'Product Short Description', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_price',
                    'name'   => esc_html__( 'Product Price', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_rating',
                    'name'   => esc_html__( 'Product Rating', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_reviews',
                    'name'   => esc_html__( 'Product Reviews', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_image',
                    'name'   => esc_html__( 'Product Image', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_product_video_gallery',
                    'name'   => esc_html__( 'Product Video Gallery', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_upsell',
                    'name'   => esc_html__( 'Product Upsell', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_stock',
                    'name'   => esc_html__( 'Product Stock Status', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_meta',
                    'name'   => esc_html__( 'Product Meta Info', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_sku',
                    'name'   => esc_html__( 'Product SKU', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),
    
                array(
                    'id'    => 'wb_product_tags',
                    'name'   => esc_html__( 'Product Tags', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),
    
                array(
                    'id'    => 'wb_product_categories',
                    'name'   => esc_html__( 'Product Categories', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_call_for_price',
                    'name'   => esc_html__( 'Call for Price', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_suggest_price',
                    'name'   => esc_html__( 'Suggest Price', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wb_product_qr_code',
                    'name'   => esc_html__( 'QR Code', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'    => 'wl_product_advance_thumbnailsp',
                    'name'   => esc_html__( 'Advance Product Image', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_product_advance_thumbnails_zoomp',
                    'name'   => esc_html__( 'Product Zoom', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_social_sherep',
                    'name'   => esc_html__( 'Product Social Share', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_stock_progress_barp',
                    'name'   => esc_html__( 'Stock Progress Bar', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),
                array(
                    'id'    => 'wl_single_product_sale_schedulep',
                    'name'   => esc_html__( 'Product Sale Schedule', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_related_productp',
                    'name'   => esc_html__( 'Related Pro..( Custom )', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_product_upsell_customp',
                    'name'   => esc_html__( 'Upsell Pro..( Custom )', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_single_pdoduct_navigation',
                    'name'   => esc_html__( 'Product Navigation', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro'=> true,
                ),

                array(
                    'id'      => 'cart_widget_heading',
                    'heading'  => esc_html__( 'Cart', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'id'    => 'wl_cart_tablep',
                    'name'   => esc_html__( 'Product Cart Table', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_cart_totalp',
                    'name'   => esc_html__( 'Product Cart Total', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_cartempty_messagep',
                    'name'   => esc_html__( 'Empty Cart Message', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_cartempty_shopredirectp',
                    'name'   => esc_html__( 'Empty Cart Re.. Button', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_cross_sellp',
                    'name'   => esc_html__( 'Product Cross Sell', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_cross_sell_customp',
                    'name'   => esc_html__( 'Cross Sell ..( Custom )', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'      => 'checkout_widget_heading',
                    'heading'  => esc_html__( 'Checkout', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'id'    => 'wl_checkout_billingp',
                    'name'   => esc_html__( 'Checkout Billing Form', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_checkout_shipping_formp',
                    'name'   => esc_html__( 'Checkout Shipping Form', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_checkout_additional_formp',
                    'name'   => esc_html__( 'Checkout Additional..', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_checkout_paymentp',
                    'name'   => esc_html__( 'Checkout Payment', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true,
                ),

                array(
                    'id'    => 'wl_checkout_coupon_formp',
                    'name'   => esc_html__( 'Checkout Co.. Form', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_checkout_login_formp',
                    'name'   => esc_html__( 'Checkout lo.. Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_order_reviewp',
                    'name'   => esc_html__( 'Checkout Order Review', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'      => 'myaccount_widget_heading',
                    'heading'  => esc_html__( 'My Account', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'id'  => 'wl_myaccount_accountp',
                    'name'   => esc_html__( 'My Account', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_navigationp',
                    'name'   => esc_html__( 'My Account Navigation', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_dashboardp',
                    'name'   => esc_html__( 'My Account Dashboard', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_downloadp',
                    'name'   => esc_html__( 'My Account Download', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_edit_accountp',
                    'name'   => esc_html__( 'My Account Edit', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_addressp',
                    'name'   => esc_html__( 'My Account Address', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_login_formp',
                    'name'   => esc_html__( 'Login Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_register_formp',
                    'name'   => esc_html__( 'Registration Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_logoutp',
                    'name'   => esc_html__( 'My Account Logout', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_orderp',
                    'name'   => esc_html__( 'My Account Order', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_myaccount_lostpasswordp',
                    'name'   => esc_html__( 'Lost Password Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),
    
                array(
                    'id'  => 'wl_myaccount_resetpasswordp',
                    'name'   => esc_html__( 'Reset Password Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'      => 'thankyou_widget_heading',
                    'heading'  => esc_html__( 'Thank You', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'id'  => 'wl_thankyou_orderp',
                    'name'   => esc_html__( 'Thank You Order', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_thankyou_customer_address_detailsp',
                    'name'   => esc_html__( 'Thank You Cus.. Address', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_thankyou_order_detailsp',
                    'name'   => esc_html__( 'Thank You Order Details', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'      => 'additional_widget_heading',
                    'heading'  => esc_html__( 'Additional', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'id'  => 'wl_mini_cartp',
                    'name'   => esc_html__( 'Mini Cart', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),

                array(
                    'id'  => 'wl_quickview_product_imgp',
                    'name'   => esc_html__( 'Quick view .. image', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                ),
            ),

            'woolentor_others_tabs' => array(
                array(
                    'id'     => 'woolentor_rename_label_tabs',
                    'name'    => esc_html__( 'Rename Label', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_rename_label_tabs',
                    'option_id'=> 'enablerenamelabel',
                    'require_settings'=> true,
                    'documentation' => esc_url('https://woolentor.com/doc/change-woocommerce-text/'),
                    // 'preview' => esc_url('https://woolentor.com/doc/change-woocommerce-text/'),
                    'setting_fields' => array(
                        
                        array(
                            'id'  => 'enablerenamelabel',
                            'name' => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'  => esc_html__( 'You can enable / disable rename label from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class'   =>'enablerenamelabel woolentor-action-field-left',
                        ),
        
                        array(
                            'id'      => 'shop_page_heading',
                            'heading'  => esc_html__( 'Shop Page', 'woolentor' ),
                            'type'      => 'title'
                        ),
                        
                        array(
                            'id'        => 'wl_shop_add_to_cart_txt',
                            'name'       => esc_html__( 'Add to Cart Button Text', 'woolentor' ),
                            'desc'        => esc_html__( 'Change the Add to Cart button text for the Shop page.', 'woolentor' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Add to Cart', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'id'      => 'product_details_page_heading',
                            'heading'  => esc_html__( 'Product Details Page', 'woolentor' ),
                            'type'      => 'title',
                        ),
        
                        array(
                            'id'        => 'wl_add_to_cart_txt',
                            'name'       => esc_html__( 'Add to Cart Button Text', 'woolentor' ),
                            'desc'        => esc_html__( 'Change the Add to Cart button text for the Product details page.', 'woolentor' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Add to Cart', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'id'        => 'wl_description_tab_menu_title',
                            'name'       => esc_html__( 'Description', 'woolentor' ),
                            'desc'        => esc_html__( 'Change the tab title for the product description.', 'woolentor' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Description', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
                        
                        array(
                            'id'        => 'wl_additional_information_tab_menu_title',
                            'name'       => esc_html__( 'Additional Information', 'woolentor' ),
                            'desc'        => esc_html__( 'Change the tab title for the product additional information', 'woolentor' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Additional information', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
                        
                        array(
                            'id'        => 'wl_reviews_tab_menu_title',
                            'name'       => esc_html__( 'Reviews', 'woolentor' ),
                            'desc'        => esc_html__( 'Change the tab title for the product review', 'woolentor' ),
                            'type'        => 'text',
                            'placeholder' => __( 'Reviews', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'id'      => 'checkout_page_heading',
                            'heading'  => esc_html__( 'Checkout Page', 'woolentor' ),
                            'type'      => 'title',
                        ),
        
                        array(
                            'id'        => 'wl_checkout_placeorder_btn_txt',
                            'name'       => esc_html__( 'Place order', 'woolentor' ),
                            'desc'        => esc_html__( 'Change the label for the Place order field.', 'woolentor' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Place order', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                    )
                ),

                array(
                    'id'     => 'woolentor_sales_notification_tabs',
                    'name'    => esc_html__( 'Sales Notification', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_sales_notification_tabs',
                    'option_id'=> 'enableresalenotification',
                    'require_settings'=> true,
                    'documentation' => esc_url('https://woolentor.com/doc/sales-notification-for-woocommerce/'),
                    'setting_fields' => array(

                        array(
                            'id'  => 'enableresalenotification',
                            'name' => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'  => esc_html__( 'You can enable / disable sales notification from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left',
                            'label_on' => __( 'ON', 'woolentor' ),
                            'label_off' => __( 'OFF', 'woolentor' ),
                        ),
                        
                        array(
                            'id'    => 'notification_content_type',
                            'name'   => esc_html__( 'Notification Content Type', 'woolentor' ),
                            'desc'    => esc_html__( 'Select Content Type', 'woolentor' ),
                            'type'    => 'radio',
                            'default' => 'actual',
                            'options' => array(
                                'actual' => esc_html__('Real','woolentor'),
                                'fakes'  => esc_html__('Manual','woolentor'),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'    => 'noification_fake_data',
                            'name'   => esc_html__( 'Choose Template', 'woolentor' ),
                            'desc'    => esc_html__( 'Choose template for manual notification.', 'woolentor' ),
                            'type'    => 'multiselect',
                            'default' => '',
                            'options' => woolentor_elementor_template(),
                            'condition' => array(
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'fakes'
                            ),
                            'placeholder' => esc_html__( 'Select Template', 'woolentor' ),
                        ),
        
                        array(
                            'id'    => 'notification_pos',
                            'name'   => esc_html__( 'Position', 'woolentor' ),
                            'desc'    => esc_html__( 'Set the position of the Sales Notification Position on frontend.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => 'bottomleft',
                            'options' => array(
                                'topleft'       => esc_html__( 'Top Left','woolentor' ),
                                'topright'      => esc_html__( 'Top Right','woolentor' ),
                                'bottomleft'    => esc_html__( 'Bottom Left','woolentor' ),
                                'bottomright'   => esc_html__( 'Bottom Right','woolentor' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'    => 'notification_layout',
                            'name'   => esc_html__( 'Image Position', 'woolentor' ),
                            'desc'    => esc_html__( 'Set the image position of the notification.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => 'imageleft',
                            'options' => array(
                                'imageleft'   => esc_html__( 'Image Left','woolentor' ),
                                'imageright'  => esc_html__( 'Image Right','woolentor' ),
                            ),
                            'condition' => array(
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ),
                            'class'   => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'    => 'notification_timing_area_title',
                            'heading'=> esc_html__( 'Notification Timing', 'woolentor' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class'   => 'element_section_title_area',
                        ),
        
                        array(
                            'id'    => 'notification_loadduration',
                            'name'   => esc_html__( 'First loading time', 'woolentor' ),
                            'desc'    => esc_html__( 'When to start notification load duration.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => '3',
                            'options' => array(
                                '2'    => esc_html__( '2 seconds','woolentor' ),
                                '3'    => esc_html__( '3 seconds','woolentor' ),
                                '4'    => esc_html__( '4 seconds','woolentor' ),
                                '5'    => esc_html__( '5 seconds','woolentor' ),
                                '6'    => esc_html__( '6 seconds','woolentor' ),
                                '7'    => esc_html__( '7 seconds','woolentor' ),
                                '8'    => esc_html__( '8 seconds','woolentor' ),
                                '9'    => esc_html__( '9 seconds','woolentor' ),
                                '10'   => esc_html__( '10 seconds','woolentor' ),
                                '20'   => esc_html__( '20 seconds','woolentor' ),
                                '30'   => esc_html__( '30 seconds','woolentor' ),
                                '40'   => esc_html__( '40 seconds','woolentor' ),
                                '50'   => esc_html__( '50 seconds','woolentor' ),
                                '60'   => esc_html__( '1 minute','woolentor' ),
                                '90'   => esc_html__( '1.5 minutes','woolentor' ),
                                '120'  => esc_html__( '2 minutes','woolentor' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'    => 'notification_time_showing',
                            'name'   => esc_html__( 'Notification showing time', 'woolentor' ),
                            'desc'    => esc_html__( 'How long to keep the notification.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => '4',
                            'options' => array(
                                '2'   => esc_html__( '2 seconds','woolentor' ),
                                '4'   => esc_html__( '4 seconds','woolentor' ),
                                '5'   => esc_html__( '5 seconds','woolentor' ),
                                '6'   => esc_html__( '6 seconds','woolentor' ),
                                '7'   => esc_html__( '7 seconds','woolentor' ),
                                '8'   => esc_html__( '8 seconds','woolentor' ),
                                '9'   => esc_html__( '9 seconds','woolentor' ),
                                '10'  => esc_html__( '10 seconds','woolentor' ),
                                '20'  => esc_html__( '20 seconds','woolentor' ),
                                '30'  => esc_html__( '30 seconds','woolentor' ),
                                '40'  => esc_html__( '40 seconds','woolentor' ),
                                '50'  => esc_html__( '50 seconds','woolentor' ),
                                '60'  => esc_html__( '1 minute','woolentor' ),
                                '90'  => esc_html__( '1.5 minutes','woolentor' ),
                                '120' => esc_html__( '2 minutes','woolentor' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'    => 'notification_time_int',
                            'name'   => esc_html__( 'Time Interval', 'woolentor' ),
                            'desc'    => esc_html__( 'Set the interval time between notifications.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => '4',
                            'options' => array(
                                '2'   => esc_html__( '2 seconds','woolentor' ),
                                '4'   => esc_html__( '4 seconds','woolentor' ),
                                '5'   => esc_html__( '5 seconds','woolentor' ),
                                '6'   => esc_html__( '6 seconds','woolentor' ),
                                '7'   => esc_html__( '7 seconds','woolentor' ),
                                '8'   => esc_html__( '8 seconds','woolentor' ),
                                '9'   => esc_html__( '9 seconds','woolentor' ),
                                '10'  => esc_html__( '10 seconds','woolentor' ),
                                '20'  => esc_html__( '20 seconds','woolentor' ),
                                '30'  => esc_html__( '30 seconds','woolentor' ),
                                '40'  => esc_html__( '40 seconds','woolentor' ),
                                '50'  => esc_html__( '50 seconds','woolentor' ),
                                '60'  => esc_html__( '1 minute','woolentor' ),
                                '90'  => esc_html__( '1.5 minutes','woolentor' ),
                                '120' => esc_html__( '2 minutes','woolentor' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'    => 'notification_product_display_option_title',
                            'heading'=> esc_html__( 'Product Query Option', 'woolentor' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class'   => 'element_section_title_area',
                        ),
        
                        array(
                            'id'              => 'notification_limit',
                            'name'             => esc_html__( 'Limit', 'woolentor' ),
                            'desc'              => esc_html__( 'Set the number of notifications to display.', 'woolentor' ),
                            'min'               => 1,
                            'max'               => 100,
                            'default'           => '5',
                            'step'              => '1',
                            'type'              => 'number',
                            'sanitize_callback' => 'number',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class'       => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'id'  => 'showallproduct',
                            'name' => esc_html__( 'Show/Display all products from each order', 'woolentor' ),
                            'desc'  => esc_html__( 'Manage show all product from each order.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class'   => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'id'    => 'notification_uptodate',
                            'name'   => esc_html__( 'Order Upto', 'woolentor' ),
                            'desc'    => esc_html__( 'Do not show purchases older than.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => '7',
                            'options' => array(
                                '1'   => esc_html__( '1 day','woolentor' ),
                                '2'   => esc_html__( '2 days','woolentor' ),
                                '3'   => esc_html__( '3 days','woolentor' ),
                                '4'   => esc_html__( '4 days','woolentor' ),
                                '5'   => esc_html__( '5 days','woolentor' ),
                                '6'   => esc_html__( '6 days','woolentor' ),
                                '7'   => esc_html__( '1 week','woolentor' ),
                                '10'  => esc_html__( '10 days','woolentor' ),
                                '14'  => esc_html__( '2 weeks','woolentor' ),
                                '21'  => esc_html__( '3 weeks','woolentor' ),
                                '28'  => esc_html__( '4 weeks','woolentor' ),
                                '35'  => esc_html__( '5 weeks','woolentor' ),
                                '42'  => esc_html__( '6 weeks','woolentor' ),
                                '49'  => esc_html__( '7 weeks','woolentor' ),
                                '56'  => esc_html__( '8 weeks','woolentor' ),
                            ),
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'id'    => 'notification_display_item_option_title',
                            'heading'=> esc_html__( 'Display Item and Custom Label', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class'   => 'element_section_title_area',
                        ),
                        array(
                            'id'  => 'show_buyer_name',
                            'name' => esc_html__( 'Show Buyer Name', 'woolentor' ),
                            'desc'  => esc_html__( 'You can display / hide Buyer Name from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class'   => 'woolentor-action-field-left',
                        ),
                        array(
                            'id'  => 'show_city',
                            'name' => esc_html__( 'Show City', 'woolentor' ),
                            'desc'  => esc_html__( 'You can display / hide city from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class'   => 'woolentor-action-field-left',
                        ),
                        array(
                            'id'  => 'show_state',
                            'name' => esc_html__( 'Show State', 'woolentor' ),
                            'desc'  => esc_html__( 'You can display / hide state from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class'   => 'woolentor-action-field-left',
                        ),
                        array(
                            'id'  => 'show_country',
                            'name' => esc_html__( 'Show Country', 'woolentor' ),
                            'desc'  => esc_html__( 'You can display / hide country from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class'   => 'woolentor-action-field-left',
                        ),

                        array(
                            'id'        => 'purchased_by',
                            'name'       => esc_html__( 'Purchased By Label', 'woolentor' ),
                            'desc'        => esc_html__( 'You can insert a label for the purchased by text.', 'woolentor' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'By', 'woolentor-pro' ),
                            'placeholder' => esc_html__( 'By', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left'
                        ),
                        array(
                            'id'        => 'price_prefix',
                            'name'       => esc_html__( 'Price Label', 'woolentor' ),
                            'desc'        => esc_html__( 'You can insert a label for the price.', 'woolentor' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'Price :', 'woolentor-pro' ),
                            'placeholder' => esc_html__( 'Price :', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'    => 'notification_animation_area_title',
                            'heading'=> esc_html__( 'Animation', 'woolentor' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class'   => 'element_section_title_area',
                        ),
        
                        array(
                            'id'    => 'notification_inanimation',
                            'name'   => esc_html__( 'Animation In', 'woolentor' ),
                            'desc'    => esc_html__( 'Choose entrance animation.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => 'fadeInLeft',
                            'options' => array(
                                'bounce'            => esc_html__( 'bounce','woolentor' ),
                                'flash'             => esc_html__( 'flash','woolentor' ),
                                'pulse'             => esc_html__( 'pulse','woolentor' ),
                                'rubberBand'        => esc_html__( 'rubberBand','woolentor' ),
                                'shake'             => esc_html__( 'shake','woolentor' ),
                                'swing'             => esc_html__( 'swing','woolentor' ),
                                'tada'              => esc_html__( 'tada','woolentor' ),
                                'wobble'            => esc_html__( 'wobble','woolentor' ),
                                'jello'             => esc_html__( 'jello','woolentor' ),
                                'heartBeat'         => esc_html__( 'heartBeat','woolentor' ),
                                'bounceIn'          => esc_html__( 'bounceIn','woolentor' ),
                                'bounceInDown'      => esc_html__( 'bounceInDown','woolentor' ),
                                'bounceInLeft'      => esc_html__( 'bounceInLeft','woolentor' ),
                                'bounceInRight'     => esc_html__( 'bounceInRight','woolentor' ),
                                'bounceInUp'        => esc_html__( 'bounceInUp','woolentor' ),
                                'fadeIn'            => esc_html__( 'fadeIn','woolentor' ),
                                'fadeInDown'        => esc_html__( 'fadeInDown','woolentor' ),
                                'fadeInDownBig'     => esc_html__( 'fadeInDownBig','woolentor' ),
                                'fadeInLeft'        => esc_html__( 'fadeInLeft','woolentor' ),
                                'fadeInLeftBig'     => esc_html__( 'fadeInLeftBig','woolentor' ),
                                'fadeInRight'       => esc_html__( 'fadeInRight','woolentor' ),
                                'fadeInRightBig'    => esc_html__( 'fadeInRightBig','woolentor' ),
                                'fadeInUp'          => esc_html__( 'fadeInUp','woolentor' ),
                                'fadeInUpBig'       => esc_html__( 'fadeInUpBig','woolentor' ),
                                'flip'              => esc_html__( 'flip','woolentor' ),
                                'flipInX'           => esc_html__( 'flipInX','woolentor' ),
                                'flipInY'           => esc_html__( 'flipInY','woolentor' ),
                                'lightSpeedIn'      => esc_html__( 'lightSpeedIn','woolentor' ),
                                'rotateIn'          => esc_html__( 'rotateIn','woolentor' ),
                                'rotateInDownLeft'  => esc_html__( 'rotateInDownLeft','woolentor' ),
                                'rotateInDownRight' => esc_html__( 'rotateInDownRight','woolentor' ),
                                'rotateInUpLeft'    => esc_html__( 'rotateInUpLeft','woolentor' ),
                                'rotateInUpRight'   => esc_html__( 'rotateInUpRight','woolentor' ),
                                'slideInUp'         => esc_html__( 'slideInUp','woolentor' ),
                                'slideInDown'       => esc_html__( 'slideInDown','woolentor' ),
                                'slideInLeft'       => esc_html__( 'slideInLeft','woolentor' ),
                                'slideInRight'      => esc_html__( 'slideInRight','woolentor' ),
                                'zoomIn'            => esc_html__( 'zoomIn','woolentor' ),
                                'zoomInDown'        => esc_html__( 'zoomInDown','woolentor' ),
                                'zoomInLeft'        => esc_html__( 'zoomInLeft','woolentor' ),
                                'zoomInRight'       => esc_html__( 'zoomInRight','woolentor' ),
                                'zoomInUp'          => esc_html__( 'zoomInUp','woolentor' ),
                                'hinge'             => esc_html__( 'hinge','woolentor' ),
                                'jackInTheBox'      => esc_html__( 'jackInTheBox','woolentor' ),
                                'rollIn'            => esc_html__( 'rollIn','woolentor' ),
                                'rollOut'           => esc_html__( 'rollOut','woolentor' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'    => 'notification_outanimation',
                            'name'   => esc_html__( 'Animation Out', 'woolentor' ),
                            'desc'    => esc_html__( 'Choose exit animation.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => 'fadeOutRight',
                            'options' => array(
                                'bounce'             => esc_html__( 'bounce','woolentor' ),
                                'flash'              => esc_html__( 'flash','woolentor' ),
                                'pulse'              => esc_html__( 'pulse','woolentor' ),
                                'rubberBand'         => esc_html__( 'rubberBand','woolentor' ),
                                'shake'              => esc_html__( 'shake','woolentor' ),
                                'swing'              => esc_html__( 'swing','woolentor' ),
                                'tada'               => esc_html__( 'tada','woolentor' ),
                                'wobble'             => esc_html__( 'wobble','woolentor' ),
                                'jello'              => esc_html__( 'jello','woolentor' ),
                                'heartBeat'          => esc_html__( 'heartBeat','woolentor' ),
                                'bounceOut'          => esc_html__( 'bounceOut','woolentor' ),
                                'bounceOutDown'      => esc_html__( 'bounceOutDown','woolentor' ),
                                'bounceOutLeft'      => esc_html__( 'bounceOutLeft','woolentor' ),
                                'bounceOutRight'     => esc_html__( 'bounceOutRight','woolentor' ),
                                'bounceOutUp'        => esc_html__( 'bounceOutUp','woolentor' ),
                                'fadeOut'            => esc_html__( 'fadeOut','woolentor' ),
                                'fadeOutDown'        => esc_html__( 'fadeOutDown','woolentor' ),
                                'fadeOutDownBig'     => esc_html__( 'fadeOutDownBig','woolentor' ),
                                'fadeOutLeft'        => esc_html__( 'fadeOutLeft','woolentor' ),
                                'fadeOutLeftBig'     => esc_html__( 'fadeOutLeftBig','woolentor' ),
                                'fadeOutRight'       => esc_html__( 'fadeOutRight','woolentor' ),
                                'fadeOutRightBig'    => esc_html__( 'fadeOutRightBig','woolentor' ),
                                'fadeOutUp'          => esc_html__( 'fadeOutUp','woolentor' ),
                                'fadeOutUpBig'       => esc_html__( 'fadeOutUpBig','woolentor' ),
                                'flip'               => esc_html__( 'flip','woolentor' ),
                                'flipOutX'           => esc_html__( 'flipOutX','woolentor' ),
                                'flipOutY'           => esc_html__( 'flipOutY','woolentor' ),
                                'lightSpeedOut'      => esc_html__( 'lightSpeedOut','woolentor' ),
                                'rotateOut'          => esc_html__( 'rotateOut','woolentor' ),
                                'rotateOutDownLeft'  => esc_html__( 'rotateOutDownLeft','woolentor' ),
                                'rotateOutDownRight' => esc_html__( 'rotateOutDownRight','woolentor' ),
                                'rotateOutUpLeft'    => esc_html__( 'rotateOutUpLeft','woolentor' ),
                                'rotateOutUpRight'   => esc_html__( 'rotateOutUpRight','woolentor' ),
                                'slideOutUp'         => esc_html__( 'slideOutUp','woolentor' ),
                                'slideOutDown'       => esc_html__( 'slideOutDown','woolentor' ),
                                'slideOutLeft'       => esc_html__( 'slideOutLeft','woolentor' ),
                                'slideOutRight'      => esc_html__( 'slideOutRight','woolentor' ),
                                'zoomOut'            => esc_html__( 'zoomOut','woolentor' ),
                                'zoomOutDown'        => esc_html__( 'zoomOutDown','woolentor' ),
                                'zoomOutLeft'        => esc_html__( 'zoomOutLeft','woolentor' ),
                                'zoomOutRight'       => esc_html__( 'zoomOutRight','woolentor' ),
                                'zoomOutUp'          => esc_html__( 'zoomOutUp','woolentor' ),
                                'hinge'              => esc_html__( 'hinge','woolentor' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
                        
                        array(
                            'id'    => 'notification_style_area_title',
                            'heading'=> esc_html__( 'Style', 'woolentor' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class' => 'element_section_title_area',
                        ),
        
                        array(
                            'id'        => 'notification_width',
                            'name'       => esc_html__( 'Width', 'woolentor' ),
                            'desc'        => esc_html__( 'You can handle the sales notification width.', 'woolentor' ),
                            'type'        => 'text',
                            'default'     => esc_html__( '550px', 'woolentor' ),
                            'placeholder' => esc_html__( '550px', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'        => 'notification_mobile_width',
                            'name'       => esc_html__( 'Width for mobile', 'woolentor' ),
                            'desc'        => esc_html__( 'You can handle the sales notification width.', 'woolentor' ),
                            'type'        => 'text',
                            'default'     => esc_html__( '90%', 'woolentor' ),
                            'placeholder' => esc_html__( '90%', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'id'  => 'background_color',
                            'name' => esc_html__( 'Background Color', 'woolentor' ),
                            'desc'  => esc_html__( 'Set the background color of the sales notification.', 'woolentor' ),
                            'type'  => 'color',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class' => 'woolentor-action-field-left',
                            'size' => 'large',
                        ),
        
                        array(
                            'id'  => 'heading_color',
                            'name' => esc_html__( 'Heading Color', 'woolentor' ),
                            'desc'  => esc_html__( 'Set the heading color of the sales notification.', 'woolentor' ),
                            'type'  => 'color',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class' => 'woolentor-action-field-left',
                            'size' => 'large',
                        ),
        
                        array(
                            'id'  => 'content_color',
                            'name' => esc_html__( 'Content Color', 'woolentor' ),
                            'desc'  => esc_html__( 'Set the content color of the sales notification.', 'woolentor' ),
                            'type'  => 'color',
                            'condition' => [
                                'key' => 'notification_content_type',
                                'operator' => '==',
                                'value' => 'actual'
                            ],
                            'class' => 'woolentor-action-field-left',
                            'size' => 'large',
                        ),
        
                        array(
                            'id'  => 'cross_color',
                            'name' => esc_html__( 'Cross Icon Color', 'woolentor' ),
                            'desc'  => esc_html__( 'Set the cross icon color of the sales notification.', 'woolentor' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                            'size' => 'large',
                        ),

                    )
                ),

                array(
                    'id'     => 'woolentor_shopify_checkout_settings',
                    'name'    => esc_html__( 'Shopify Style Checkout', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_shopify_checkout_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/how-to-make-woocommerce-checkout-like-shopify/'),
                    'setting_fields' => array(

                        array(
                            'id'  => 'enable',
                            'name' => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'  => esc_html__( 'You can enable / disable shopify style checkout page from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'    => 'logo',
                            'name'   => esc_html__( 'Logo', 'woolentor' ),
                            'desc'    => esc_html__( 'You can upload your logo for shopify style checkout page from here.', 'woolentor' ),
                            'type'    => 'imageupload',
                            'options' => [
                                'button_label'        => esc_html__( 'Upload', 'woolentor' ),   
                                'button_remove_label' => esc_html__( 'Remove', 'woolentor' ),   
                            ],
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'        => 'logo_page',
                            'name'       => esc_html__( 'Logo URL', 'woolentor' ),
                            'desc'        => esc_html__( 'Link your logo to an existing page or a custom URL.', 'woolentor' ),
                            'type'        => 'select',
                            'options'     => (['custom'=> esc_html__( 'Custom URL', 'woolentor' )] + woolentor_post_name( 'page', ['limit'=>-1] )),
                            'default'     => '0',
                            'condition' => [
                                'key' => 'logo',
                                'operator' => '!=',
                                'value' => ''
                            ],
                            'class'       => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'        => 'logo_custom_url',
                            'name'       => esc_html__( 'Custom URL', 'woolentor' ),
                            'desc'        => esc_html__( 'Insert a custom URL for the logo.', 'woolentor' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'your-domain.com', 'woolentor' ),
                            'condition' => [
                                'key' => 'logo_page',
                                'operator' => '==',
                                'value' => 'custom'
                            ],
                            'class'       => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'    => 'custommenu',
                            'name'   => esc_html__( 'Bottom Menu', 'woolentor' ),
                            'desc'    => esc_html__( 'You can choose menu for shopify style checkout page.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => '0',
                            'options' => array( '0'=> esc_html__('Select Menu','woolentor') ) + woolentor_get_all_create_menus(),
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'    => 'show_phone',
                            'name'   => esc_html__( 'Show Phone Number Field', 'woolentor' ),
                            'desc'    => esc_html__( 'Show the Phone Number Field.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'    => 'show_company',
                            'name'   => esc_html__( 'Show Company Name Field', 'woolentor' ),
                            'desc'    => esc_html__( 'Show the Company Name Field.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'    => 'hide_cart_nivigation',
                            'name'   => esc_html__( 'Hide Cart Navigation', 'woolentor' ),
                            'desc'    => esc_html__( 'Hide the "Cart" menu and "Return to cart" button.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'    => 'hide_shipping_step',
                            'name'   => esc_html__( 'Hide Shipping Step', 'woolentor' ),
                            'desc'    => esc_html__( 'Turn it ON to hide the "Shipping" Step.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'        => 'customize_labels',
                            'name'       => esc_html__( 'Rename Labels?', 'woolentor' ),
                            'desc'        => esc_html__( 'Enable it to customize labels of the checkout page.', 'woolentor' ),
                            'type'        => 'checkbox',
                            'class'       => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'        => 'labels_list',
                            'name'       => esc_html__( 'Labels', 'woolentor' ),
                            'type'        => 'repeater',
                            'title_field' => 'select_tab',
                            'condition' => [
                                'key' => 'customize_labels',
                                'operator' => '==',
                                'value' => 'on'
                            ],
                            'options' => [
                                'button_label' => esc_html__( 'Add Custom Label', 'woolentor' ),
                            ],
                            'max_items' => '3',
                            'fields'  => [

                                array(
                                    'id'    => 'select_tab',
                                    'name'   => esc_html__( 'Select Tab', 'woolentor' ),
                                    'desc'    => esc_html__( 'Select the tab for which you want to change the labels. ', 'woolentor' ),
                                    'type'    => 'select',
                                    'class'   => 'woolentor-action-field-left',
                                    'default' => 'information',
                                    'options' => array(
                                        'information'  => esc_html__('Information','woolentor'),
                                        'shipping'     => esc_html__('Shipping','woolentor'),
                                        'payment'      => esc_html__('Payment','woolentor'),
                                    ),
                                ),

                                array(
                                    'id'        => 'tab_label',
                                    'name'       => esc_html__( 'Tab Label', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left',
                                ),

                                array(
                                    'id'        => 'label_1',
                                    'name'       => esc_html__( 'Button Label One', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left',
                                ),

                                array(
                                    'id'        => 'label_2',
                                    'name'       => esc_html__( 'Button Label Two', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left',
                                ),

                            ]
                        ),
                        
                    )

                ),

                array(
                    'id'     => 'woolentor_flash_sale_settings',
                    'name'    => esc_html__( 'Flash Sale Countdown', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_flash_sale_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/enable-sales-countdown-timer-in-woocommerce/'),
                    'setting_fields' => array(

                        array(
                            'id'  => 'enable',
                            'name' => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'  => esc_html__( 'You can enable / disable flash sale from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'        => 'deals',
                            'name'       => esc_html__( 'Sale Events', 'woolentor' ),
                            'type'        => 'repeater',
                            'title_field' => 'title',
                            'fields'  => [

                                array(
                                    'id'        => 'title',
                                    'name'       => esc_html__( 'Event Name', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left',
                                    'condition' => [
                                        'key' => 'status',
                                        'operator' => '==',
                                        'value' => 'on'
                                    ],
                                ),

                                array(
                                    'id'        => 'status',
                                    'name'       => esc_html__( 'Enable', 'woolentor' ),
                                    'desc'        => esc_html__( 'Enable / Disable', 'woolentor' ),
                                    'type'        => 'checkbox',
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'id'        => 'start_date',
                                    'name'       => esc_html__( 'Valid From', 'woolentor' ),
                                    'desc'        => __( 'The date and time the event should be enabled. Please set time based on your server time settings. Current Server Date / Time: '. current_time('Y M d'), 'woolentor' ),
                                    'type'        => 'date',
                                    'condition' => [
                                        'key' => 'status',
                                        'operator' => '==',
                                        'value' => 'on'
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'id'        => 'end_date',
                                    'name'       => esc_html__( 'Valid To', 'woolentor' ),
                                    'desc'        => esc_html__( 'The date and time the event should be disabled.', 'woolentor' ),
                                    'type'        => 'date',
                                    'condition' => [
                                        'key' => 'status',
                                        'operator' => '==',
                                        'value' => 'on'
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'id'        => 'apply_on_all_products',
                                    'name'       => esc_html__( 'Apply On All Products', 'woolentor' ),
                                    'type'        => 'checkbox',
                                    'default'     => 'off',
                                    'condition' => [
                                        'key' => 'status',
                                        'operator' => '==',
                                        'value' => 'on'
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'id'        => 'categories',
                                    'name'       => esc_html__( 'Select Categories', 'woolentor' ),
                                    'desc'        => esc_html__( 'Select the categories in which products the discount will be applied.', 'woolentor' ),
                                    'type'        => 'multiselect',
                                    'options'     => woolentor_taxonomy_list('product_cat','term_id'),
                                    'convertnumber' => true,
                                    'condition' => [
                                        'key' => 'status|apply_on_all_products',
                                        'operator' => '==|==',
                                        'value' => 'on|off'
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'id'        => 'products',
                                    'name'       => esc_html__( 'Select Products', 'woolentor' ),
                                    'desc'        => esc_html__( 'Select individual products in which the discount will be applied.', 'woolentor' ),
                                    'type'        => 'multiselect',
                                    'options'     => woolentor_post_name( 'product' ),
                                    'convertnumber' => true,
                                    'condition' => [
                                        'key' => 'status|apply_on_all_products',
                                        'operator' => '==|==',
                                        'value' => 'on|off'
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'id'        => 'exclude_products',
                                    'name'       => esc_html__( 'Exclude Products', 'woolentor' ),
                                    'type'        => 'multiselect',
                                    'options'     => woolentor_post_name( 'product' ),
                                    'convertnumber' => true,
                                    'condition' => [
                                        'key' => 'status',
                                        'operator' => '==',
                                        'value' => 'on'
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'id'        => 'discount_type',
                                    'name'       => esc_html__( 'Discount Type', 'woolentor' ),
                                    'type'        => 'select',
                                    'default'     => 'percentage_discount',
                                    'options'     => array(
                                        'fixed_discount'      => esc_html__( 'Fixed Discount', 'woolentor' ),
                                        'percentage_discount' => esc_html__( 'Percentage Discount', 'woolentor' ),
                                        'fixed_price'         => esc_html__( 'Fixed Price', 'woolentor' ),
                                    ),
                                    'condition' => [
                                        'key' => 'status',
                                        'operator' => '==',
                                        'value' => 'on'
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'id'  => 'discount_value',
                                    'name'       => esc_html__( 'Discount Value', 'woolentor' ),
                                    'min'               => 0.0,
                                    'step'              => 0.01,
                                    'type'              => 'number',
                                    'default'           => '50',
                                    'sanitize_callback' => 'floatval',
                                    'condition' => [
                                        'key' => 'status',
                                        'operator' => '==',
                                        'value' => 'on'
                                    ],
                                    'class'             => 'woolentor-action-field-left',
                                ),

                                array(
                                    'id'        => 'apply_discount_only_for_registered_customers',
                                    'name'       => esc_html__( 'Apply Discount Only For Registered Customers', 'woolentor' ),
                                    'type'        => 'checkbox',
                                    'condition' => [
                                        'key' => 'status',
                                        'operator' => '==',
                                        'value' => 'on'
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                            ]
                        ),

                        array(
                            'id'          => 'manage_price_label',
                            'name'       => esc_html__( 'Manage Price Label', 'woolentor' ),
                            'desc'        => esc_html__( 'Manage how you want the price labels to appear, or leave it blank to display only the flash-sale price without any labels. Available placeholders: {original_price}, {flash_sale_price}', 'woolentor' ),
                            'type'        => 'text',
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'id'    => 'override_sale_price',
                            'name'   => esc_html__( 'Override Sale Price', 'woolentor' ),
                            'type'    => 'checkbox',
                            'default' => 'off',
                            'class'   => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'    => 'enable_countdown_on_product_details_page',
                            'name'   => esc_html__( 'Show Countdown On Product Details Page', 'woolentor' ),
                            'type'    => 'checkbox',
                            'default' => 'on',
                            'class'   => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'      => 'countdown_style',
                            'name'     => esc_html__( 'Countdown Style', 'woolentor' ),
                            'type'      => 'select',
                            'options'   => array(
                               '1'      => esc_html__('Style One', 'woolentor'),
                               '2'      => esc_html__('Style Two', 'woolentor'),
                            ),
                            'default'   => '2',
                            'condition' => [
                                'key' => 'enable_countdown_on_product_details_page',
                                'operator' => '==',
                                'value' => 'on'
                            ],
                            'class'     => 'woolentor-action-field-left'
                        ),

                         array(
                             'id'        => 'countdown_position',
                             'name'       => esc_html__( 'Countdown Position', 'woolentor' ),
                             'type'        => 'select',
                             'options'     => array(
                                'woocommerce_before_add_to_cart_form'      => esc_html__('Add to cart - Before', 'woolentor'),
                                'woocommerce_after_add_to_cart_form'       => esc_html__('Add to cart - After', 'woolentor'),
                                'woocommerce_product_meta_start'           => esc_html__('Product meta - Before', 'woolentor'),
                                'woocommerce_product_meta_end'             => esc_html__('Product meta - After', 'woolentor'),
                                'woocommerce_single_product_summary'       => esc_html__('Product summary - Before', 'woolentor'),
                                'woocommerce_after_single_product_summary' => esc_html__('Product summary - After', 'woolentor'),
                             ),
                             'condition'   => [
                                'key' => 'enable_countdown_on_product_details_page',
                                'operator' => '==',
                                'value' => 'on'
                            ],
                             'class'       => 'woolentor-action-field-left'
                         ),

                        array(
                            'id'    => 'countdown_timer_title',
                            'name'   => esc_html__( 'Countdown Timer Title', 'woolentor' ),
                            'type'    => 'text',
                            'default' => esc_html__('Hurry Up! Offer ends in', 'woolentor'),
                            'condition' => [
                                'key' => 'enable_countdown_on_product_details_page',
                                'operator' => '==',
                                'value' => 'on'
                            ],
                            'class'   => 'woolentor-action-field-left'
                        ),
                        
                    )

                ),

                array(
                    'id'     => 'woolentor_backorder_settings',
                    'name'    => esc_html__( 'Backorder', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_backorder_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/how-to-enable-woocommerce-backorder/'),
                    'setting_fields' => array(
                    
                        array(
                            'id'  => 'enable',
                            'name' => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'  => esc_html__( 'You can enable / disable backorder module from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'    => 'backorder_limit',
                            'name'   => esc_html__( 'Backorder Limit', 'woolentor' ),
                            'desc'    => esc_html__( 'Set "Backorder Limit" on all "Backorder" products across the entire website. You can also set limits for each product individually from the "Inventory" tab.', 'woolentor' ),
                            'type'    => 'number',
                            'class'   => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'    => 'backorder_availability_date',
                            'name'   => esc_html__( 'Availability Date', 'woolentor' ),
                            'type'    => 'date',
                            'class'   => 'woolentor-action-field-left'
                        ),
                    
                        array(
                            'id'          => 'backorder_availability_message',
                            'name'       => esc_html__( 'Availability Message', 'woolentor' ),
                            'desc'        => esc_html__( 'Manage how you want the "Message" to appear. Use this {availability_date} placeholder to display the date you set. ', 'woolentor' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'On Backorder: Will be available on {availability_date}', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
                        
                    )
                    
                ),

                array(
                    'id'     => 'woolentor_swatch_settings',
                    'name'    => esc_html__( 'Variation Swatches', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_swatch_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/variation-swatches/'),
                    'setting_fields' => array(

                        array(
                            'id'    => 'enable',
                            'name'   => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'    => esc_html__( 'Enable / disable this module.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'default' => 'off',
                            'class'   => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'       => 'sp_enable_swatches',
                            'name'      => esc_html__( 'Enable On Product Details Page', 'woolentor' ),
                            'desc'       => esc_html__( 'Enable Swatches for the Product Details pages.', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'on',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),

                        array(
                            'id'       => 'pl_enable_swatches',
                            'name'      => esc_html__( 'Enable On Shop / Archive Page', 'woolentor' ),
                            'desc'       => esc_html__( 'Enable Swatches for the products in the Shop / Archive Pages', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'off',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),

                        array(
                            'id'       => 'heading_1',
                            'type'       => 'title',
                            'heading'   => esc_html__( 'General Options', 'woolentor' ),
                            'size'       => 'woolentor_style_seperator',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),
        
                        array(
                            'id'       => 'auto_convert_dropdowns_to_label',
                            'name'      => esc_html__( 'Auto Convert Dropdowns To Label', 'woolentor' ),
                            'desc'       => esc_html__( 'Automatically convert dropdowns to "label swatch" by default.', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'on',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),

                        array(
                            'id'       => 'auto_convert_dropdowns_to_image',
                            'name'      => esc_html__( 'Auto Convert Dropdowns To Image', 'woolentor' ),
                            'desc'       => esc_html__( 'Automatically convert dropdowns to "Image Swatch" if variation has an image.', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'off',
                            'class'      => 'woolentor-action-field-left woolentor-adv-pro-notice',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            ),
                            'is_pro'     => true
                        ),

                        array(
                            'id'    => 'auto_convert_dropdowns_to_image_condition',
                            'name'   => esc_html__( 'Apply Auto Image For', 'woolentor' ),
                            'type'    => 'select',
                            'class'   => 'woolentor-action-field-left',
                            'default' => 'first_attribute',
                            'options' => array(
                                'first_attribute' => esc_html__('The First attribute', 'woolentor'),
                                'maximum'         => esc_html__('The attribute with Maximum variations count', 'woolentor'),
                                'minimum'         => esc_html__('The attribute with Minimum variations count', 'woolentor'),
                            ),
                            'condition'  => array(
                                'key'=>'enable|auto_convert_dropdowns_to_image', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            )
                        ),

                        array(
                            'id'       => 'tooltip',
                            'name'      => esc_html__( 'Tooltip', 'woolentor' ),
                            'desc'       => esc_html__( 'Enable Tooltip', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'on',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),
                        
                        array(
                            'id'    => 'swatch_width_height',
                            'name'   => esc_html__( 'Swatch Width & Height', 'woolentor' ),
                            'desc'    => esc_html__( 'Change Swatch Width and Height From Here.', 'woolentor' ),
                            'type'    => 'dimensions',
                            'options' => [
                                'width'   => esc_html__( 'Width', 'woolentor' ),
                                'height'  => esc_html__( 'Height', 'woolentor' ),
                                'unit'    => esc_html__( 'Unit', 'woolentor' ),
                            ],
                            'default' => array(
                                'unit' => 'px',
                                'width' => 33,
                                'height' => 33
                            ),
                            'class'       => 'woolentor-action-field-left woolentor-dimention-field-left',
                            'condition'   => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),

                        array(
                            'id'    => 'tooltip_width_height',
                            'name'   => esc_html__( 'Tooltip Width', 'woolentor' ),
                            'desc'    => esc_html__( 'Change Tooltip Width From Here.', 'woolentor' ),
                            'type'    => 'dimensions',
                            'options' => [
                                'width'   => esc_html__( 'Width', 'woolentor' ),
                                'unit'    => esc_html__( 'Unit', 'woolentor' ),  
                            ],
                            'default' => array(
                                'unit' => 'px'
                            ),
                            'class'       => 'woolentor-action-field-left woolentor-dimention-field-left',
                            'condition'   => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),

                        array(
                            'id'       => 'show_swatch_image_in_tooltip',
                            'name'      => esc_html__('Swatch Image as Tooltip', 'woolentor'),
                            'type'       => 'checkbox',
                            'desc'       => esc_html__('If you check this options. When a watch type is "image" and has an image. The image will be shown into the tooltip.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),
                        
                        array(
                            'id'       => 'ajax_variation_threshold',
                            'name'      => esc_html__('Change AJAX Variation Threshold', 'woolentor'),
                            'type'      => 'number',
                            'min'       => '0',
                            'max'       => '100',
                            'default'   => '30',
                            'class'     => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            ),
                            'tooltip'    => [
                                'text' => __('If a variable product has over 30 variants, WooCommerce doesn\'t allow you to show which combinations are unavailable for purchase. That\'s why customers need to check each combination to see if it is available or not. Although you can increase the threshold, keeping it at a standard value is recommended, so it doesn\'t negatively impact your website\'s performance.
                                <br/>Here \"standard value\" refers to the number of highest combinations you have set for one of your products.','woolentor'),
                                'placement' => 'top',
                            ],
                        ),

                        array(
                            'id'    => 'shape_style',
                            'name'   => esc_html__('Shape Style', 'woolentor'),
                            'type'    => 'select',
                            'options' => array(
                                'squared' => esc_html__('Squared', 'woolentor'),
                                'rounded' => esc_html__('Rounded', 'woolentor'),
                                'circle'  => esc_html__('Circle', 'woolentor'),
                            ),
                            'default'    => 'squared',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),

                        array(
                            'id'       => 'enable_shape_inset',
                            'name'      => esc_html__('Enable Shape Inset', 'woolentor'),
                            'type'       => 'checkbox',
                            'desc'       => esc_html__('Shape inset is the empty space arround the swatch.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),

                        array(
                            'id'       => 'show_selected_attribute_name',
                            'name'      => esc_html__('Show Selected Variation Name', 'woolentor'),
                            'type'       => 'checkbox',
                            'default'    => 'on',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),

                        array(
                            'id'         => 'variation_label_separator',
                            'name'        => esc_html__('Variation Label Separator', 'woolentor'),
                            'type'         => 'text',
                            'default'      => esc_html__(' : ', 'woolentor'),
                            'class'        => 'woolentor-action-field-left',
                            'condition'    => array( 
                                'key'=>'enable|show_selected_attribute_name', 
                                'operator'=>'==|==', 
                                'value'=>'on|on' 
                            ),
                        ),

                        array(
                            'id'  => 'disabled_attribute_type',
                            'name' => esc_html__('Disabled Attribute Type', 'woolentor'),
                            'type'  => 'select',
                            'options' => array(
                                ''                => esc_html__('Cross Sign', 'woolentor'),
                                'blur_with_cross' => esc_html__('Blur With Cross', 'woolentor'),
                                'blur'            => esc_html__('Blur', 'woolentor'),
                                'hide'            => esc_html__('Hide', 'woolentor'),
                            ),
                            'desc'       => esc_html__('Note: It will not effective when you have large number of variations but the "Ajax Variation Threshold" value is less than the number of variations.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            )
                        ),

                        array(
                            'id'       => 'disable_out_of_stock',
                            'name'      => esc_html__('Disable Variation Form for The "Out of Stock" Products', 'woolentor'),
                            'type'       => 'checkbox',
                            'desc'       => esc_html__('If disabled, an out of stock message will be shown instead of showing the variations form / swatches.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left  woolentor-adv-pro-notice',
                            'condition'  => array(
                                'key'=>'enable',
                                'operator'=>'==',
                                'value'=>'on'
                            ),
                            'is_pro'     => true
                        ),

                        // Archive page options
                        array(
                            'id'      => 'heading_2',
                            'type'      => 'title',
                            'heading'  => esc_html__( 'Shop / Archive Page Swatch Options', 'woolentor' ),
                            'size'      => 'woolentor_style_seperator',
                            'condition' => array( 
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                        ),

                        array(
                            'id'      => 'pl_show_swatches_label',
                            'name'     =>  esc_html__('Show Swatches Label', 'woolentor'),
                            'type'      => 'checkbox',
                            'class'     => 'woolentor-action-field-left',
                            'condition' => array( 
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                        ),

                        array(
                            'id'      => 'pl_show_clear_link',
                            'name'     =>  esc_html__('Show Clear Button', 'woolentor'),
                            'type'      => 'checkbox',
                            'class'     => 'woolentor-action-field-left',
                            'default'   => 'on',
                            'condition' => array( 
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                        ),

                        array(
                            'id'    => 'pl_align',
                            'name'   => esc_html__('Swatches Align', 'woolentor'),
                            'type'    => 'select',
                            'options' => array(
                                'left'   => esc_html__('Left', 'woolentor'),
                                'center' => esc_html__('Center', 'woolentor'),
                                'right'  => esc_html__('Right', 'woolentor'),
                            ),
                            'default'   => 'center',
                            'class'     => 'woolentor-action-field-left',
                            'condition' => array( 
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                        ),

                        array(
                            'id'    => 'pl_position',
                            'name'   => esc_html__('Swatches Position', 'woolentor'),
                            'type'    => 'select',
                            'options' => array(
                                'before_title'    => esc_html__('Before Title', 'woolentor'),
                                'after_title'     => esc_html__('After Title', 'woolentor'),
                                'before_price'    => esc_html__('Before Price', 'woolentor'),
                                'after_price'     => esc_html__('After Price', 'woolentor'),
                                'custom_position' => esc_html__('Custom Position', 'woolentor'),
                                'shortcode'       => esc_html__('Use Shortcode', 'woolentor'),
                            ),
                            'default'   => 'after_title',
                            'class'     => 'woolentor-action-field-left',
                            'condition' => array( 
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                        ),

                        array(
                            'id' => 'short_code_display',
                            'name'   => esc_html__('Swatches Shortcode', 'woolentor'),
                            'type'=>'html',
                            'html'=>'<code>[swatchly_pl_swatches]</code> Use this shortcode to show the variation Swatches.',
                            'condition' => array( 
                                'key'=>'pl_position', 
                                'operator'=>'==', 
                                'value'=>'shortcode'
                            ),
                        ),

                        array(
                            'id'       => 'pl_custom_position_hook_name',
                            'name'      =>  esc_html__('Hook Name', 'woolentor'),
                            'type'       => 'text',
                            'desc'       =>  esc_html__('e.g: woocommerce_after_shop_loop_item_title', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable|pl_enable_swatches|pl_position', 
                                'operator'=>'==|==|==', 
                                'value'=>'on|on|custom_position'
                            ),
                        ), 

                        array(
                            'id'       => 'pl_custom_position_hook_priority',
                            'name'      =>  esc_html__('Hook Priority', 'woolentor'),
                            'type'       => 'text',
                            'desc'       =>  esc_html__('Default: 10', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array(
                                'key'=>'enable|pl_enable_swatches|pl_position', 
                                'operator'=>'==|==|==', 
                                'value'=>'on|on|custom_position'
                            ),
                        ), 

                        array(
                            'id'        => 'pl_product_thumbnail_selector',
                            'name'       =>  esc_html__('Product Thumbnail Selector', 'woolentor'),
                            'type'        => 'text',
                            'placeholder' => esc_html__('Example: img.attachment-woocommerce_thumbnail', 'woolentor'),
                            'class'       => 'woolentor-action-field-left',
                            'condition'   => array(
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                            'tooltip'     => [
                                'text' => esc_html__( 'Some themes remove the default product image. In this case, variation image will not be changed after choose a variation. Here you can place the CSS selector of the product thumbnail, so the product image will be chagned once a variation is choosen.', 'woolentor' ),
                                'placement' => 'top',
                            ],
                        ), 

                        array(
                            'id'         => 'pl_enable_ajax_add_to_cart',
                            'name'        =>  esc_html__('Enable AJAX Add to Cart', 'woolentor'),
                            'type'         => 'checkbox',
                            'class'        => 'woolentor-action-field-left woolentor-adv-pro-notice',
                            'condition'    => array(
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                            'is_pro'     => true
                        ),

                        array(
                            'id'         => 'pl_enable_swatch_limit',
                            'name'        =>  esc_html__('Enable Swatch Limit', 'woolentor'),
                            'type'         => 'checkbox',
                            'class'        => 'woolentor-action-field-left',
                            'condition'    => array(
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                            'is_pro'       => true
                        ),

                        array(
                            'id'      => 'pl_enable_catalog_mode_heading',
                            'heading'  => esc_html__( 'Shop / Archive Page - Catalog Mode', 'woolentor-pro' ),
                            'type'      => 'title',
                            'condition'    => array(
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                            'is_pro'       => true
                        ),

                        array(
                            'id'         => 'pl_enable_catalog_mode',
                            'name'        =>  esc_html__('Enable Catalog Mode', 'woolentor'),
                            'type'         => 'checkbox',
                            'class'        => 'woolentor-action-field-left',
                            'condition'    => array(
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                            'is_pro'       => true
                        ),

                        array(
                            'id'       => 'pl_add_to_cart_text',
                            'name'      =>  esc_html__('Add to Cart Text', 'woolentor'),
                            'type'       => 'text',
                            'desc'       =>  esc_html__('Leave it empty for default.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left woolentor-adv-pro-opacity',
                            'condition'  => array(
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                            'is_pro'     => true
                        ),

                        array(
                            'id'       => 'pl_hide_wc_forward_button',
                            'name'      =>  esc_html__('Hide "View Cart" button after Added to Cart', 'woolentor'),
                            'type'       => 'checkbox',
                            'desc'       =>  esc_html__('After successfully add to cart, a new button shows linked to the cart page. You can controll of that button from here. Note: If redirect option is enable from WooCommerce it will not work.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left woolentor-adv-pro-opacity',
                            'condition'  => array(
                                'key'=>'enable|pl_enable_swatches', 
                                'operator'=>'==|==', 
                                'value'=>'on|on'
                            ),
                            'is_pro'     => true
                        ),

                        array(
                            'id'         => 'pl_enable_cart_popup_notice',
                            'name'        =>  esc_html__('Enable poupup notice after added to cart', 'woolentor'),
                            'type'         => 'checkbox',
                            'desc'         =>  esc_html__('After successfully add to cart, a pupup notice will be generated containing a button linked to the cart page. Note: If redirect option is enable from WooCommerce it will not work.', 'woolentor'),
                            'class'        => 'woolentor-action-field-left woolentor-adv-pro-opacity',
                            'condition'    => array(
                                'key' => 'enable|pl_enable_swatches', 
                                'operator' => '==|==', 
                                'value' => 'on|on'
                            ),
                            'is_pro'       => true
                        ),
                        

                    )

                ),

                array(
                    'id'     => 'woolentor_popup_builder_settings',
                    'name'    => esc_html__( 'Popup Builder', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_popup_builder_settings',
                    'option_id'=> 'enable',
                    'documentation' => esc_url('https://woolentor.com/doc/popup-builder/'),
                    'require_settings'  => true,
                    'setting_fields' => array(

                        array(
                            'id'    => 'enable',
                            'name'   => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'    => esc_html__( 'Enable / disable this module.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'default' => 'off',
                            'class'   => 'woolentor-action-field-left'
                        ),

                        array(
                            'id'  => 'width',
                            'name' => esc_html__( 'Popup Width', 'woolentor' ),
                            'desc'  => esc_html__( 'You can set the container width of the Popup area. Example: 600px', 'woolentor' ),
                            'type'              => 'text',
                            'default'           => '600px',
                            'class'             => 'woolentor-action-field-left',
                        ),

                        array(
                            'id'  => 'height',
                            'name' => esc_html__( 'Popup Height', 'woolentor' ),
                            'desc'  => esc_html__( 'You can set the container height of the Popup area. Example: 600px', 'woolentor' ),
                            'type'              => 'text',
                            'class'             => 'woolentor-action-field-left',
                        ),

                        array(
                            'id'  => 'z_index',
                            'name' => esc_html__( 'Z-Index', 'woolentor' ),
                            'desc'  => __( 'You can set the z-index of the Popup. <br>Example: 9999', 'woolentor' ),
                            'type'              => 'number',
                            'class'             => 'woolentor-action-field-left',
                            'default'          => '9999',
                        ),

                        array(
                            'id'        => 'go_popup_template_builder',
                            'name'       => esc_html__( 'Go Builder', 'woolentor-pro' ),
                            'html'        => wp_kses_post( '<a href="'.admin_url('edit.php?post_type=woolentor-template&template_type=popup&tabs=popup').'" target="_blank">Create or Import Popups from here.</a>' ),
                            'type'        => 'html',
                            'class'       => 'woolentor-action-field-left'
                        ),

                    )
                ),

                array(
                    'id'     => 'wishlist',
                    'name'    => esc_html__( 'Wishlist', 'woolentor' ),
                    'type'     => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'documentation' => esc_url('https://woolentor.com/doc/wishlist-for-woocommerce/')
                ),
                array(
                    'id'     => 'compare',
                    'name'    => esc_html__( 'Compare', 'woolentor' ),
                    'type'     => 'element',
                    'default'  => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'documentation' => esc_url('https://woolentor.com/doc/woocommerce-product-compare/')
                ),
                
                array(
                    'id'     => 'ajaxsearch',
                    'name'    => esc_html__( 'Ajax Search Widget', 'woolentor' ),
                    'desc'    => esc_html__( 'AJAX Search Widget', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'documentation' => esc_url('https://woolentor.com/doc/how-to-use-woocommerce-ajax-search/')
                ),

                array(
                    'id'     => 'ajaxcart_singleproduct',
                    'name'    => esc_html__( 'Single Product Ajax Add To Cart', 'woolentor' ),
                    'desc'    => esc_html__( 'AJAX Add to Cart on Single Product page', 'woolentor' ),
                    'type'     => 'element',
                    'default'  => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'documentation' => esc_url('https://woolentor.com/doc/single-product-ajax-add-to-cart/')
                ),

                array(
                    'id'   => 'woolentor_checkout_field_settingsp',
                    'name'  => esc_html__( 'Checkout Fields Manager', 'woolentor' ),
                    'desc'  => esc_html__( 'Checkout Fields Manager Module', 'woolentor' ),
                    'type'   => 'module',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'require_settings' => true,
                    'is_pro' => true
                ),

                array(
                    'id'   => 'partial_paymentp',
                    'name'  => esc_html__( 'Partial Payment', 'woolentor' ),
                    'desc'   => esc_html__( 'Partial Payment Module', 'woolentor' ),
                    'type'   => 'module',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'require_settings' => true,
                    'is_pro' => true
                ),

                array(
                    'id'   => 'pre_ordersp',
                    'name'  => esc_html__( 'Pre Orders', 'woolentor' ),
                    'desc'  => esc_html__( 'Pre Orders Module', 'woolentor' ),
                    'type'   => 'module',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'require_settings' => true,
                    'is_pro' => true
                ),

                array(
                    'id'   => 'size_chartp',
                    'name'  => esc_html__( 'Size Chart', 'woolentor' ),
                    'desc'  => esc_html__( 'Size Chart Module', 'woolentor' ),
                    'type'   => 'module',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'require_settings' => true,
                    'is_pro' => true
                ),

                array(
                    'id'   => 'order_bump',
                    'name'   => esc_html__( 'Order Bump', 'woolentor' ),
                    'type'    => 'module',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'require_settings' => true,
                    'is_pro'  => true
                ),

                array(
                    'id'   => 'product_filterp',
                    'name'   => esc_html__( 'Product Filter', 'woolentor' ),
                    'type'    => 'module',
                    'default' => 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'require_settings' => true,
                    'is_pro'  => true
                ),

                array(
                    'id'     => 'email_customizerp',
                    'name'   => esc_html__( 'Email Customizer', 'woolentor' ),
                    'type'     => 'module',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'require_settings' => true,
                    'is_pro' => true
                ),

                array(
                    'id'     => 'email_automationp',
                    'name'   => esc_html__( 'Email Automation', 'woolentor' ),
                    'type'     => 'module',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'require_settings' => true,
                    'is_pro' => true
                ),

                array(
                    'id'   => 'gtm_conversion_trackingp',
                    'name'   => esc_html__( 'GTM Conversion Tracking', 'woolentor' ),
                    'desc'   => esc_html__( 'GTM Conversion Tracking Module', 'woolentor' ),
                    'type'   => 'module',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'require_settings' => true,
                    'is_pro' => true
                ),
                
                array(
                    'id'   => 'single_product_sticky_add_to_cartp',
                    'name'   => esc_html__( 'Single Product Sticky Add To Cart', 'woolentor' ),
                    'desc'   => esc_html__( 'Sticky Add to Cart on Single Product page', 'woolentor' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true
                ),

                array(
                    'id'   => 'mini_side_cartp',
                    'name'  => esc_html__( 'Side Mini Cart', 'woolentor' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true
                ),

                array(
                    'id'   => 'redirect_add_to_cartp',
                    'name'  => esc_html__( 'Redirect to Checkout', 'woolentor' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true
                ),

                array(
                    'id'   => 'multi_step_checkoutp',
                    'name'  => esc_html__( 'Multi Step Checkout', 'woolentor' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true
                ),

                array(
                    'id'     => 'quick_checkoutp',
                    'name'    => esc_html__( 'Quick Checkout', 'woolentor' ),
                    'type'     => 'module',
                    'default'=> 'off',
                    'require_settings' => true,
                    'label_on' => __( 'ON', 'woolentor' ),
                    'label_off' => __( 'OFF', 'woolentor' ),
                    'is_pro' => true
                ),

                array(
                    'id'  => 'loadproductlimit',
                    'name' => esc_html__( 'Load Products in Elementor Addons', 'woolentor' ),
                    'desc'  => esc_html__( 'Set the number of products to load in Elementor Addons', 'woolentor' ),
                    'min'               => 1,
                    'max'               => 100,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '20',
                    'sanitize_callback' => 'floatval',
                    'column'            => 1,
                )

            ),

            'woolentor_style_tabs' => array(

                array(
                    'id'     => 'section_area_title_heading',
                    'type'     => 'title',
                    'heading' => esc_html__( 'Universal layout style options', 'woolentor' ),
                    'size'     => 'woolentor_style_seperator',
                ),

                array(
                    'id'        => 'content_area_bg',
                    'name'     => esc_html__( 'Content area background', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ffffff',
                ),

                array(
                    'id'        => 'section_title_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Title', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'id'      => 'title_color',
                    'name'     => esc_html__( 'Title color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),
                array(
                    'id'      => 'title_hover_color',
                    'name'     => esc_html__( 'Title hover color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),

                array(
                    'id'      => 'section_price_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Price', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'id'      => 'sale_price_color',
                    'name'     => esc_html__( 'Sale price color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),
                array(
                    'id'      => 'regular_price_color',
                    'name'     => esc_html__( 'Regular price color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),

                array(
                    'id'      => 'section_category_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Category', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'id'      => 'category_color',
                    'name'     => esc_html__( 'Category color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),
                array(
                    'id'      => 'category_hover_color',
                    'name'     => esc_html__( 'Category hover color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),

                array(
                    'id'      => 'section_short_description_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Short Description', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'id'      => 'desc_color',
                    'name'     => esc_html__( 'Description color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),

                array(
                    'id'      => 'section_rating_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Rating', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'id'      => 'empty_rating_color',
                    'name'     => esc_html__( 'Empty rating color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#aaaaaa',
                ),
                array(
                    'id'      => 'rating_color',
                    'name'     => esc_html__( 'Rating color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),

                array(
                    'id'      => 'section_badge_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Product Badge', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'id'      => 'badge_color',
                    'name'     => esc_html__( 'Badge color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),

                array(
                    'id'      => 'section_action_btn_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Quick Action Button', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'id'      => 'tooltip_color',
                    'name'     => esc_html__( 'Tool tip color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ffffff',
                ),
                array(
                    'id'      => 'btn_color',
                    'name'     => esc_html__( 'Button color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#000000',
                ),
                array(
                    'id'      => 'btn_hover_color',
                    'name'     => esc_html__( 'Button hover color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),

                array(
                    'id'      => 'section_action_list_btn_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Archive List View Action Button', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'id'      => 'list_btn_color',
                    'name'     => esc_html__( 'List View Button color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#000000',
                ),
                array(
                    'id'      => 'list_btn_hover_color',
                    'name'     => esc_html__( 'List View Button Hover color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),
                array(
                    'id'      => 'list_btn_bg_color',
                    'name'     => esc_html__( 'List View Button background color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ffffff',
                ),
                array(
                    'id'      => 'list_btn_hover_bg_color',
                    'name'     => esc_html__( 'List View Button hover background color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ff3535',
                ),

                array(
                    'id'      => 'section_counter_timer_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Counter Timer', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'id'      => 'counter_color',
                    'name'     => esc_html__( 'Counter timer color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ffffff',
                ),

                array(
                    'id'      => 'section_helping_screenshot_heading',
                    'type'      => 'title',
                    'heading'  => esc_html__( 'Helping Screenshot', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),

                array(
                    'id'   => 'helping_screenshot',
                    'type' => 'html',
                    'html' => '<img src="' . WOOLENTOROPT_ASSETS . '/images/helping-screenshot.png' . '" alt="' . esc_attr__('Helping Screenshot','woolentor') . '">'
                ),

            ),

            'woolentor_free_extension_tabs' => array(
                array(
                    'id'   => 'extension_fee_content',
                    'type' => 'html',
                    'html' => esc_html__('Loading...','woolentor'),
                    'component' => 'FreeExtension'
                )
            ),
            'woolentor_pro_extension_tabs' => array(
                array(
                    'id'   => 'extension_pro_content',
                    'type' => 'html',
                    'html' => esc_html__('Loading...','woolentor'),
                    'component' => 'ProExtension'
                )
            ),

            'woolentor_freevspro_tabs' => array(
                array(
                    'id'   => 'fee_vs_pro_content',
                    'type' => 'html',
                    'html' => esc_html__('Loading...','woolentor'),
                    'api_info' =>[
                        'url'=>'/woolentoropt/v1/free-vs-pro',
                    ]
                )
            )

        );

        // Post Duplicator Condition
        if( !is_plugin_active('ht-mega-for-elementor/htmega_addons_elementor.php') ){

            $post_types = woolentor_get_post_types( array( 'defaultadd' => 'all' ) );
            if ( did_action( 'elementor/loaded' ) && defined( 'ELEMENTOR_VERSION' ) ) {
                $post_types['elementor_library'] = esc_html__( 'Templates', 'woolentor' );
            }

            // Add Option in array before the last element
            $lastKey = array_key_last($settings['woolentor_others_tabs']);
            $lastValue = $settings['woolentor_others_tabs'][$lastKey];
            unset($settings['woolentor_others_tabs'][$lastKey]);

            $settings['woolentor_others_tabs'][] = [
                'id'     => 'postduplicator',
                'name'    => esc_html__( 'Post Duplicator', 'woolentor' ),
                'type'     => 'element',
                'default'  => 'off',
                'require_settings'  => true,
                'documentation' => esc_url('https://woolentor.com/doc/duplicate-woocommerce-product/'),
                'parent_id' => 'woolentor_others_tabs',
                'setting_fields' => array(
                    
                    array(
                        'id'    => 'postduplicate_condition',
                        'name'   => esc_html__( 'Post Duplicator Condition', 'woolentor' ),
                        'desc'    => esc_html__( 'You can enable duplicator for individual post.', 'woolentor' ),
                        'type'    => 'multiselect',
                        'default' => '',
                        'options' => $post_types,
                        'class' => 'woolentor-full-width-field'
                    )

                )
            ];

            $settings['woolentor_others_tabs'][] = $lastValue;

        }
    
        // return $settings;
        // return apply_filters( 'woolentor_admin_fields', $settings );
        return apply_filters( 'woolentor_admin_fields_vue', $settings );

    }

}