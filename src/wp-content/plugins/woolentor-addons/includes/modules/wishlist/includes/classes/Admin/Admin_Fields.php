<?php
namespace WishSuite\Admin;
use WooLentor\Traits\Singleton;
/**
 * Admin Page Fields handlers class
 */
class Admin_Fields {
    use Singleton;

    private $settings_api;

    function __construct() {
        require_once( WOOLENTOR_ADDONS_PL_PATH .'includes/admin/include/settings_field_manager_default.php' );
        $this->settings_api = new \WooLentor_Settings_Field_Manager_Default();
        add_action( 'admin_init', [ $this, 'admin_init' ] );
    }

    public function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Options page Section register
    public function get_settings_sections() {
        $sections = array(

            array(
                'id'    => 'wishsuite_general_tabs',
                'title' => esc_html__( 'General Settings', 'woolentor' )
            ),

            array(
                'id'    => 'wishsuite_settings_tabs',
                'title' => esc_html__( 'Button Settings', 'woolentor' )
            ),
            
            array(
                'id'    => 'wishsuite_table_settings_tabs',
                'title' => esc_html__( 'Table Settings', 'woolentor' )
            ),
            
            array(
                'id'    => 'wishsuite_style_settings_tabs',
                'title' => esc_html__( 'Style Settings', 'woolentor' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function fields_settings() {

        $settings_fields = array(

            'wishsuite_general_tabs' => array(
                array(
                    'name'      => 'enable_login_limit',
                    'label'     => __( 'Limit Wishlist Use', 'woolentor' ),
                    'type'      => 'checkbox',
                    'default'   => 'off',
                    'desc'      => esc_html__( 'Enable this option to allow only the logged-in users to use the Wishlist feature.', 'woolentor' ),
                ),

                array(
                    'name'      => 'logout_button',
                    'label'     => __( 'Wishlist Icon Tooltip Text', 'woolentor' ),
                    'desc'      => __( 'Enter a text for the tooltip that will be shown when someone hover over the Wishlist icon.', 'woolentor' ),
                    'type'      => 'text',
                    'default'   => __( 'Please login', 'woolentor' ),
                     'class'    => 'depend_user_login_enable'
                ),

            ),

            'wishsuite_settings_tabs' => array(

                array(
                    'name'  => 'btn_show_shoppage',
                    'label'  => __( 'Show button in product list', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                ),

                array(
                    'name'  => 'btn_show_productpage',
                    'label'  => __( 'Show button in single product page', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name'    => 'shop_btn_position',
                    'label'   => __( 'Shop page button position', 'woolentor' ),
                    'desc'    => __( 'You can manage wishlist button position in product list page.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'after_cart_btn',
                    'options' => [
                        'before_cart_btn' => __( 'Before Add To Cart', 'woolentor' ),
                        'after_cart_btn'  => __( 'After Add To Cart', 'woolentor' ),
                        'top_thumbnail'   => __( 'Top On Image', 'woolentor' ),
                        'use_shortcode'   => __( 'Use Shortcode', 'woolentor' ),
                        'custom_position' => __( 'Custom Position', 'woolentor' ),
                    ],
                ),

                array(
                    'name'    => 'shop_use_shortcode_message',
                    'headding'=> wp_kses_post('<code>[wishsuite_button]</code> Use this shortcode into your theme/child theme to place the wishlist button.'),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_shop_btn_position_use_shortcode element_section_title_area message-info',
                ),

                array(
                    'name'    => 'shop_custom_hook_message',
                    'headding'=> esc_html__( 'Some themes remove the above positions. In that case, custom position is useful. Here you can place the custom/default hook name & priority to inject & adjust the wishlist button for the product loop.', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_shop_btn_position_custom_hook element_section_title_area message-info',
                ),

                array(
                    'name'        => 'shop_custom_hook_name',
                    'label'       => __( 'Hook name', 'woolentor' ),
                    'desc'        => __( 'e.g: woocommerce_after_shop_loop_item_title', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_shop_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'shop_custom_hook_priority',
                    'label'       => __( 'Hook priority', 'woolentor' ),
                    'desc'        => __( 'Default: 10', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_shop_btn_position_custom_hook'
                ),

                array(
                    'name'    => 'product_btn_position',
                    'label'   => __( 'Product page button position', 'woolentor' ),
                    'desc'    => __( 'You can manage wishlist button position in single product page.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'after_cart_btn',
                    'options' => [
                        'before_cart_btn' => __( 'Before Add To Cart', 'woolentor' ),
                        'after_cart_btn'  => __( 'After Add To Cart', 'woolentor' ),
                        'after_thumbnail' => __( 'After Image', 'woolentor' ),
                        'after_summary'   => __( 'After Summary', 'woolentor' ),
                        'use_shortcode'   => __( 'Use Shortcode', 'woolentor' ),
                        'custom_position' => __( 'Custom Position', 'woolentor' ),
                    ],
                ),

                array(
                    'name'    => 'product_use_shortcode_message',
                    'headding'=> wp_kses_post('<code>[wishsuite_button]</code> Use this shortcode into your theme/child theme to place the wishlist button.'),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_product_btn_position_use_shortcode element_section_title_area message-info',
                ),

                array(
                    'name'    => 'product_custom_hook_message',
                    'headding'=> esc_html__( 'Some themes remove the above positions. In that case, custom position is useful. Here you can place the custom/default hook name & priority to inject & adjust the wishlist button for the single product page.', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_product_btn_position_custom_hook element_section_title_area message-info',
                ),

                array(
                    'name'        => 'product_custom_hook_name',
                    'label'       => __( 'Hook name', 'woolentor' ),
                    'desc'        => __( 'e.g: woocommerce_after_single_product_summary', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_product_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'product_custom_hook_priority',
                    'label'       => __( 'Hook priority', 'woolentor' ),
                    'desc'        => __( 'Default: 10', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_product_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'button_text',
                    'label'       => __( 'Button Text', 'woolentor' ),
                    'desc'        => __( 'Enter your wishlist button text.', 'woolentor' ),
                    'type'        => 'text',
                    'default'     => __( 'Wishlist', 'woolentor' ),
                    'placeholder' => __( 'Wishlist', 'woolentor' ),
                ),

                array(
                    'name'        => 'added_button_text',
                    'label'       => __( 'Product added text', 'woolentor' ),
                    'desc'        => __( 'Enter the product added text.', 'woolentor' ),
                    'type'        => 'text',
                    'default'     => __( 'Product Added', 'woolentor' ),
                    'placeholder' => __( 'Product Added', 'woolentor' ),
                ),

                array(
                    'name'        => 'exist_button_text',
                    'label'       => __( 'Already exists in the wishlist text', 'woolentor' ),
                    'desc'        => wp_kses_post( 'Enter the message for "<strong>already exists in the wishlist</strong>" text.' ),
                    'type'        => 'text',
                    'default'     => __( 'Product already added', 'woolentor' ),
                    'placeholder' => __( 'Product already added', 'woolentor' ),
                ),

            ),

            'wishsuite_table_settings_tabs' => array(

                array(
                    'name'    => 'wishlist_page',
                    'label'   => __( 'Wishlist page', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => wishsuite_get_post_list(),
                    'desc'    => wp_kses_post('Select a wishlist page for wishlist table. It should contain the shortcode <code>[wishsuite_table]</code>'),
                ),

                array(
                    'name'    => 'wishlist_product_per_page',
                    'label'   => __( 'Products per page', 'woolentor' ),
                    'type'    => 'number',
                    'default' => '20',
                    'desc'    => __('You can choose the number of wishlist products to display per page. The default value is 20 products.', 'woolentor'),
                ),

                array(
                    'name'  => 'after_added_to_cart',
                    'label'  => __( 'Remove from the "Wishlist" after adding to the cart.', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name' => 'show_fields',
                    'label' => __('Show fields in table', 'woolentor'),
                    'desc' => __('Choose which fields should be presented on the product compare page with table.', 'woolentor'),
                    'type' => 'multicheckshort',
                    'options' => wishsuite_get_available_attributes(),
                    'default' => [
                        'remove'        => esc_html__( 'Remove', 'woolentor' ),
                        'image'         => esc_html__( 'Image', 'woolentor' ),
                        'title'         => esc_html__( 'Title', 'woolentor' ),
                        'price'         => esc_html__( 'Price', 'woolentor' ),
                        'quantity'      => esc_html__( 'Quantity', 'woolentor' ),
                        'add_to_cart'   => esc_html__( 'Add To Cart', 'woolentor' ),
                    ],
                ),

                array(
                    'name'    => 'table_heading',
                    'label'   => __( 'Table heading text', 'woolentor' ),
                    'desc'    => __( 'You can change table heading text from here.', 'woolentor' ),
                    'type'    => 'multitext',
                    'options' => wishsuite_table_heading()
                ),

                array(
                    'name' => 'empty_table_text',
                    'label' => __('Empty table text', 'woolentor'),
                    'desc' => __('Text will be displayed if the user doesn\'t add any product to  the wishlist.', 'woolentor'),
                    'type' => 'textarea'
                ),

                array(
                    'name'        => 'image_size',
                    'label'       => __( 'Image size', 'woolentor' ),
                    'desc'        => __( 'Enter your required image size.', 'woolentor' ),
                    'type'        => 'multitext',
                    'options'     =>[
                        'width'  => esc_html__( 'Width', 'woolentor' ),
                        'height' => esc_html__( 'Height', 'woolentor' ),
                    ],
                    'default' => [
                        'width'   => 80,
                        'height'  => 80,
                    ],
                ),

                array(
                    'name'  => 'hard_crop',
                    'label'  => __( 'Image Hard Crop', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name'    => 'social_share_button_area_title',
                    'headding'=> esc_html__( 'Social share button', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'element_section_title_area',
                ),

                array(
                    'name'  => 'enable_social_share',
                    'label'  => esc_html__( 'Enable social share button', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'desc'    => esc_html__( 'Enable social share button.', 'woolentor' ),
                ),

                array(
                    'name'        => 'social_share_button_title',
                    'label'       => esc_html__( 'Social share button title', 'woolentor' ),
                    'desc'        => esc_html__( 'Enter your social share button title.', 'woolentor' ),
                    'type'        => 'text',
                    'default'     => esc_html__( 'Share:', 'woolentor' ),
                    'placeholder' => esc_html__( 'Share', 'woolentor' ),
                    'class' => 'depend_social_share_enable'
                ),

                array(
                    'name' => 'social_share_buttons',
                    'label' => esc_html__('Enable share buttons', 'woolentor'),
                    'desc'    => esc_html__( 'You can manage your social share buttons.', 'woolentor' ),
                    'type' => 'multicheckshort',
                    'options' => [
                        'facebook'      => esc_html__( 'Facebook', 'woolentor' ),
                        'twitter'       => esc_html__( 'Twitter', 'woolentor' ),
                        'pinterest'     => esc_html__( 'Pinterest', 'woolentor' ),
                        'linkedin'      => esc_html__( 'Linkedin', 'woolentor' ),
                        'email'         => esc_html__( 'Email', 'woolentor' ),
                        'reddit'        => esc_html__( 'Reddit', 'woolentor' ),
                        'telegram'      => esc_html__( 'Telegram', 'woolentor' ),
                        'odnoklassniki' => esc_html__( 'Odnoklassniki', 'woolentor' ),
                        'whatsapp'      => esc_html__( 'WhatsApp', 'woolentor' ),
                        'vk'            => esc_html__( 'VK', 'woolentor' ),
                    ],
                    'default' => [
                        'facebook'   => esc_html__( 'Facebook', 'woolentor' ),
                        'twitter'    => esc_html__( 'Twitter', 'woolentor' ),
                        'pinterest'  => esc_html__( 'Pinterest', 'woolentor' ),
                        'linkedin'   => esc_html__( 'Linkedin', 'woolentor' ),
                        'telegram'   => esc_html__( 'Telegram', 'woolentor' ),
                    ],
                    'class' => 'depend_social_share_enable'
                ),

            ),

            'wishsuite_style_settings_tabs' => array(

                array(
                    'name'    => 'button_style',
                    'label'   => __( 'Button style', 'woolentor' ),
                    'desc'    => __( 'Choose a style for the wishlist button from here.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'default'     => esc_html__( 'Default style', 'woolentor' ),
                        'themestyle'  => esc_html__( 'Theme style', 'woolentor' ),
                        'custom'      => esc_html__( 'Custom style', 'woolentor' ),
                    ]
                ),

                array(
                    'name'    => 'button_icon_type',
                    'label'   => __( 'Button icon type', 'woolentor' ),
                    'desc'    => __( 'Choose an icon for the wishlist button from here.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'none'     => esc_html__( 'None', 'woolentor' ),
                        'default'  => esc_html__( 'Default icon', 'woolentor' ),
                        'custom'   => esc_html__( 'Custom icon', 'woolentor' ),
                    ]
                ),

                array(
                    'name'    => 'button_custom_icon',
                    'label'   => __( 'Button custom icon', 'woolentor' ),
                    'type'    => 'image_upload',
                    'options' => [
                        'button_label' => esc_html__( 'Upload', 'woolentor' ),   
                        'button_remove_label' => esc_html__( 'Remove', 'woolentor' ),   
                    ],
                ),

                array(
                    'name'    => 'addedbutton_icon_type',
                    'label'   => __( 'Added Button icon type', 'woolentor' ),
                    'desc'    => __( 'Choose an icon for the wishlist button from here.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'none'     => esc_html__( 'None', 'woolentor' ),
                        'default'  => esc_html__( 'Default icon', 'woolentor' ),
                        'custom'   => esc_html__( 'Custom icon', 'woolentor' ),
                    ]
                ),

                array(
                    'name'    => 'addedbutton_custom_icon',
                    'label'   => __( 'Added Button custom icon', 'woolentor' ),
                    'type'    => 'image_upload',
                    'options' => [
                        'button_label' => esc_html__( 'Upload', 'woolentor' ),   
                        'button_remove_label' => esc_html__( 'Remove', 'woolentor' ),   
                    ],
                ),

                array(
                    'name'    => 'table_style',
                    'label'   => __( 'Table style', 'woolentor' ),
                    'desc'    => __( 'Choose a style for the wishlist table here.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'default' => esc_html__( 'Default style', 'woolentor' ),
                        'custom'  => esc_html__( 'Custom style', 'woolentor' ),
                    ]
                ),

                array(
                    'name'    => 'button_custom_style_title',
                    'headding'=> __( 'Button custom style', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'button_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'button_color',
                    'label' => esc_html__( 'Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the color of the button.' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'  => 'button_hover_color',
                    'label' => esc_html__( 'Hover Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the hover color of the button.' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'  => 'background_color',
                    'label' => esc_html__( 'Background Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the background color of the button.' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'  => 'hover_background_color',
                    'label' => esc_html__( 'Hover Background Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the hover background color of the button.' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_padding',
                    'label'   => __( 'Padding', 'woolentor' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'woolentor' ),   
                        'right' => esc_html__( 'Right', 'woolentor' ),   
                        'bottom'=> esc_html__( 'Bottom', 'woolentor' ),   
                        'left'  => esc_html__( 'Left', 'woolentor' ),
                        'unit'  => esc_html__( 'Unit', 'woolentor' ),
                    ],
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_margin',
                    'label'   => __( 'Margin', 'woolentor' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'woolentor' ),   
                        'right' => esc_html__( 'Right', 'woolentor' ),   
                        'bottom'=> esc_html__( 'Bottom', 'woolentor' ),   
                        'left'  => esc_html__( 'Left', 'woolentor' ),
                        'unit'  => esc_html__( 'Unit', 'woolentor' ),
                    ],
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_border_radius',
                    'label'   => __( 'Border Radius', 'woolentor' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'woolentor' ),   
                        'right' => esc_html__( 'Right', 'woolentor' ),   
                        'bottom'=> esc_html__( 'Bottom', 'woolentor' ),   
                        'left'  => esc_html__( 'Left', 'woolentor' ),
                        'unit'  => esc_html__( 'Unit', 'woolentor' ),
                    ],
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'table_custom_style_title',
                    'headding'=> __( 'Table custom style', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'table_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'table_heading_color',
                    'label' => esc_html__( 'Heading Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the heading color of the wishlist table.' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

                array(
                    'name'  => 'table_heading_bg_color',
                    'label' => esc_html__( 'Heading Background Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the heading background color of the wishlist table.' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_heading_border_color',
                    'label' => esc_html__( 'Heading Border Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the heading border color of the wishlist table.', ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

                array(
                    'name'  => 'table_border_color',
                    'label' => esc_html__( 'Border Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the border color of the wishlist table.' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

                array(
                    'name'    => 'table_custom_style_add_to_cart',
                    'headding'=> __( 'Add To Cart Button style', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'table_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'table_cart_button_color',
                    'label' => esc_html__( 'Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button color of the wishlist table.' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_cart_button_bg_color',
                    'label' => esc_html__( 'Background Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button background color of the wishlist table.' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_cart_button_hover_color',
                    'label' => esc_html__( 'Hover Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button hover color of the wishlist table.' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_cart_button_hover_bg_color',
                    'label' => esc_html__( 'Hover Background Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button hover background color of the wishlist table.' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

            ),

        );
        
        return $settings_fields;
    }

    public function plugin_page() {
        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'Wishlist Settings','woolentor' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';
    }

    public function save_message() {
        if( isset( $_GET['settings-updated'] ) ) {
            ?>
                <div class="updated notice is-dismissible"> 
                    <p><strong><?php esc_html_e('Successfully Settings Saved.', 'woolentor') ?></strong></p>
                </div>
            <?php
        }
    }

}