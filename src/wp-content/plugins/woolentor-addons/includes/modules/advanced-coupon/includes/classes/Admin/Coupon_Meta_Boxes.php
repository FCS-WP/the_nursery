<?php
namespace Woolentor\Modules\AdvancedCoupon\Admin;
use WooLentor\Traits\Singleton;
use Woolentor\Modules\AdvancedCoupon\Functions;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Coupon_Meta_Boxes {
    use Singleton;

    public function __construct(){

        // Coupon general option
        add_action('woocommerce_coupon_options', [$this, 'add_field_in_general_option'], 10, 2);

        // Add additional tab
        add_filter( 'woocommerce_coupon_data_tabs', [$this, 'add_additional_tab'], 20, 1 );

        // Additional tab content
        add_action( 'woocommerce_coupon_data_panels', [$this, 'additional_tab_content'], 10, 0 );

        // Save meta boxes data
        add_action('woocommerce_process_shop_coupon_meta', [$this, 'save_meta_boxes_data'], 11, 2);

    }

    /**
     * Manage General Field
     * @param mixed $coupon_id
     * @param mixed $coupon
     * @return void
     */
    public function add_field_in_general_option( $coupon_id, $coupon ){

        // Field For Start date
        $start_date = Functions::get_meta_data( $coupon_id , 'woolentor_start_date' );
        $start_date_err_msg = Functions::get_meta_data( $coupon_id , 'woolentor_start_date_error_message' );

        woocommerce_wp_text_input([
            'id'                => 'woolentor_start_date',
            'value'             => esc_attr( $start_date ),
            'label'             => esc_html__( 'Coupon start date', 'woolentor' ),
            'placeholder'       => 'YYYY-MM-DD',
            'description'       => esc_html__( 'The coupon will start at 00:00:00 of this date.', 'woolentor' ),
            'desc_tip'          => true,
            'class'             => 'date-picker',
            'custom_attributes' => [
                'pattern' => apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ),
            ],
        ]);

        woocommerce_wp_textarea_input([
            'id'          => 'woolentor_start_date_error_message',
            'label'       => esc_html__( 'Coupon start date error message', 'woolentor' ),
            'description' => esc_html__( 'Show a personalized error message to customers attempting to use a coupon before it state date.', 'woolentor' ),
            'desc_tip'    => true,
            'type'        => 'text',
            'data_type'   => 'text',
            'placeholder' => esc_html__('Sorry, this coupon isn\'t available yet! Please check back later to apply it.','woolentor'),
            'value'       => $start_date_err_msg,
        ]);


    }

    /**
     * Add Additional Tabs
     * @param mixed $tabs
     * @return mixed
     */
    public function add_additional_tab( $tabs ) {

		$tabs['woolentor_coupon_payment_shipping'] = [
			'label'  => esc_html__( 'Payment & shipping methods restriction', 'woolentor' ),
			'target' => 'woolentor_coupon_payment_shipping',
			'class'  => 'woolentor_coupon_payment_shipping',
        ];

		$tabs['woolentor_coupon_user_role'] = [
			'label'  => esc_html__( 'User role restriction', 'woolentor' ),
			'target' => 'woolentor_coupon_user_role',
			'class'  => 'woolentor_coupon_user_role',
        ];

		$tabs['woolentor_coupon_country'] = [
			'label'  => esc_html__( 'Country restriction', 'woolentor' ),
			'target' => 'woolentor_coupon_country',
			'class'  => 'woolentor_coupon_country',
        ];

        $tabs['woolentor_coupon_url'] = [
			'label'  => esc_html__( 'URL Coupons', 'woolentor' ),
			'target' => 'woolentor_coupon_url',
			'class'  => 'woolentor_coupon_url',
        ];

		return $tabs;
	}

    /**
     * Additional Tab Content
     * @return void
     */
    public function additional_tab_content() {
		global $thepostid, $post;
		$coupon_id = ( empty( $thepostid ) ? $post->ID : $thepostid );

        ?>
            <!-- Shipping and payment restriction Start -->
            <div id="woolentor_coupon_payment_shipping" class="panel woocommerce_options_panel">

                <p class="form-field">
                    <label for="woolentor_shipping_method_ids"><?php esc_html_e( 'Shipping methods', 'woolentor' ); ?></label>
					<select id="woolentor_shipping_method_ids" name="woolentor_shipping_method_ids[]" style="width:50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select shipping method', 'woolentor' ); ?>">
						<?php
                            $shipping_methods = WC()->shipping->load_shipping_methods();

                            if ( ! empty( $shipping_methods ) ) {

                                $shipping_method_ids = Functions::get_multiple_meta_date($coupon_id, 'woolentor_shipping_method_ids');

                                foreach ( $shipping_methods as $shipping_method ) {

                                    // Skip disabled items.
                                    if ( 'yes' !== $shipping_method->enabled ) {
                                        continue;
                                    }

                                    $method_title = $shipping_method->method_title;
                                    $method_id    = $shipping_method->id;

                                    if ( 'pickup_location' === $method_id ) {
                                        $method_title .= __( ' (Only for block checkout)', 'woolentor' );
                                    }

                                    echo '<option value="' . esc_attr( $method_id ) . '"' . selected( in_array( $method_id, $shipping_method_ids ), true, false ) . '>' . esc_html( wp_strip_all_tags( $method_title ) ) . '</option>';

                                }

                            }
						?>
					</select>
					<?php echo wc_help_tip( esc_html__( 'The coupon will only be applicable if the selected shipping method matches either condition.', 'woolentor' ) ); ?>
				</p>

                <div class="options_group" style="border-top:0;">
                    <?php
                        woocommerce_wp_select([
                            'id'          => 'woolentor_shipping_restrict_type',
                            'type'        => 'select',
                            'value'       => Functions::get_meta_data( $coupon_id , 'woolentor_shipping_restrict_type', 'allowed' ),
                            'options'     => [
                                'allowed'    => esc_html__( 'Allowed', 'woolentor' ),
                                'disallowed' => esc_html__( 'Disallowed', 'woolentor' ),
                            ],
                            'style'       => 'width:50%;',
                            'label'       => esc_html__( 'Shipping restrict type', 'woolentor' ),
                            'description' => esc_html__( 'The type of implementation for this restriction. Select "allowed" to allow coupon only to shipping method under the selected method. Select "disallowed" to only allow coupon to shipping that don\'t fall under the selected method.', 'woolentor' ),
                            'desc_tip'    => true,
                        ]);

                        woocommerce_wp_textarea_input([
                            'id'          => 'woolentor_shipping_error_message',
                            'label'       => esc_html__( 'Shipping error message', 'woolentor' ),
                            'description' => esc_html__( 'Show a personalized error message to customers attempting to use a coupon before it state date.', 'woolentor' ),
                            'desc_tip'    => true,
                            'type'        => 'text',
                            'data_type'   => 'text',
                            'placeholder' => esc_html__('This coupon is not applicable with your selected shipping method.','woolentor'),
                            'value'       => Functions::get_meta_data( $coupon_id , 'woolentor_shipping_error_message' ),
                        ]);

                    ?>
                </div>

                <?php do_action('woolentor_coupon_payment_fields', $coupon_id); ?>

            </div>
            <!-- Shipping and payment restriction End -->

            <!-- User Role restriction Start -->
            <div id="woolentor_coupon_user_role" class="panel woocommerce_options_panel">

                <p class="form-field">
                    <label for="woolentor_user_roles"><?php esc_html_e( 'User Roles', 'woolentor' ); ?></label>
                    <select id="woolentor_user_roles" name="woolentor_user_roles[]" style="width:50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select user role', 'woolentor' ); ?>">
                        <?php
                        $available_roles = array_reverse( get_editable_roles() );

                        if ( ! empty( $available_roles ) ) {
                            $user_roles = Functions::get_multiple_meta_date( $coupon_id, 'woolentor_user_roles' );

                            foreach ( $available_roles as $role_id => $role ) {
                                echo '<option value="' . esc_attr( $role_id ) . '"' . selected( in_array( $role_id, $user_roles ), true, false ) . '>' . esc_html( translate_user_role( $role['name'] ) ) . '</option>';
                            }
                        }
                        ?>
                    </select> 
                    <?php echo wc_help_tip(esc_html__( 'The coupon will only be applicable if the selected user role matches either condition.', 'woolentor' ) ); ?>
                </p>

                <div class="options_group" style="border-top:0;">
                    <?php
                        woocommerce_wp_select([
                            'id'          => 'woolentor_user_role_restrict_type',
                            'type'        => 'select',
                            'value'       => Functions::get_meta_data( $coupon_id , 'woolentor_user_role_restrict_type', 'allowed' ),
                            'options'     => [
                                'allowed'    => esc_html__( 'Allowed', 'woolentor' ),
                                'disallowed' => esc_html__( 'Disallowed', 'woolentor' ),
                            ],
                            'style'       => 'width:50%;',
                            'label'       => esc_html__( 'Role restrict type', 'woolentor' ),
                            'description' => esc_html__( 'The type of implementation for this restriction. Select "allowed" to allow coupon only to user role under the selected method. Select "disallowed" to only allow coupon to user role that don\'t fall under the selected user role.', 'woolentor' ),
                            'desc_tip'    => true,
                        ]);

                        woocommerce_wp_textarea_input([
                            'id'          => 'woolentor_user_role_error_message',
                            'label'       => esc_html__( 'User role error message', 'woolentor' ),
                            'description' => esc_html__( 'Display a personalized error message to customers attempting to use a coupon if the user role condition does not match.', 'woolentor' ),
                            'desc_tip'    => true,
                            'type'        => 'text',
                            'data_type'   => 'text',
                            'placeholder' => esc_html__('You are not eligible to use this coupon.','woolentor'),
                            'value'       => Functions::get_meta_data( $coupon_id , 'woolentor_user_role_error_message' ),
                        ]);

                    ?>
                </div>

            </div>
            <!-- User Role restriction End -->

            <!-- Country restriction Start -->
            <div id="woolentor_coupon_country" class="panel woocommerce_options_panel">

                <div class="options_group" style="border:none;">
                    <?php
                        woocommerce_wp_select([
                            'id'          => 'woolentor_checking_address_type',
                            'type'        => 'select',
                            'value'       => Functions::get_meta_data( $coupon_id , 'woolentor_checking_address_type', 'billing' ),
                            'options'     => [
                                'billing'  => esc_html__( 'Billing Address.', 'woolentor' ),
                                'shipping' => esc_html__( 'Shipping Address.', 'woolentor' ),
                            ],
                            'style'       => 'width:50%;',
                            'label'       => esc_html__( 'Country check in type', 'woolentor' ),
                            'description' => esc_html__( 'Country check in type', 'woolentor' ),
                            'desc_tip'    => true,
                        ]);
                    ?>
                </div>

                <p class="form-field">         
                    <label for="woolentor_countries"><?php esc_html_e( 'Country', 'woolentor' ); ?></label> 
                    <select id="woolentor_countries" name="woolentor_countries[]" style="width:50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select Countries', 'woolentor' ); ?>">
                        <?php
                            $countries_obj = new \WC_Countries();
                            $countries     = $countries_obj->__get( 'countries' );

                            if ( ! empty( $countries ) ) {
                                $available_locations = Functions::get_multiple_meta_date( $coupon_id, 'woolentor_countries' );

                                foreach ( $countries as $country_code => $country ) {
                                    echo '<option value="' . esc_attr( $country_code ) . '" ' . selected( in_array( $country_code, $available_locations ), true, false ) . '>' . esc_html( $country ) . '</option>';
                                }
                            }
                        ?>
                    </select> 
                    <?php echo wc_help_tip( esc_html__( 'The coupon will only be applicable if the selected country matches either the shipping or billing address.', 'woolentor' ) ); ?>
                </p>

                <div class="options_group" style="border-top:0;">
                    <?php
                        woocommerce_wp_select([
                            'id'          => 'woolentor_country_restrict_type',
                            'type'        => 'select',
                            'value'       => Functions::get_meta_data( $coupon_id , 'woolentor_country_restrict_type', 'allowed' ),
                            'options'     => [
                                'allowed'    => esc_html__( 'Allowed', 'woolentor' ),
                                'disallowed' => esc_html__( 'Disallowed', 'woolentor' ),
                            ],
                            'style'       => 'width:50%;',
                            'label'       => esc_html__( 'Country restrict type', 'woolentor' ),
                            'description' => esc_html__( 'The type of implementation for this restriction. Select "allowed" to allow coupon only to country under the selected countries. Select "disallowed" to only allow coupon to country that don\'t fall under the selected countries.', 'woolentor' ),
                            'desc_tip'    => true,
                        ]);

                        woocommerce_wp_textarea_input([
                            'id'          => 'woolentor_country_error_message',
                            'label'       => esc_html__( 'Country error message', 'woolentor' ),
                            'description' => esc_html__( 'Display a personalized error message to customers attempting to use a coupon if the country condition does not match.', 'woolentor' ),
                            'desc_tip'    => true,
                            'type'        => 'text',
                            'data_type'   => 'text',
                            'placeholder' => esc_html__('You are not eligible to use this coupon.','woolentor'),
                            'value'       => Functions::get_meta_data( $coupon_id , 'woolentor_country_error_message' ),
                        ]);

                    ?>
                </div>
                <!-- Country restriction end -->

            </div>
            <!-- Country restriction End -->

            <!-- URL Coupon Field Start -->
            <div id="woolentor_coupon_url" class="panel woocommerce_options_panel">
                <?php do_action('woolentor_coupon_url_fields', $coupon_id); ?>
            </div>
            <!-- URL Coupon Field End -->
        <?php
    }

    /**
     * Pro filed
     * @param mixed $coupon_id
     * @return void
     */
    public function pro_payment_option_field( $coupon_id ){
        ?>
            <div class="woolentor-coupon-pro-options options_group" style="border-top:0;">
                <fieldset>
                    <legend><?php esc_html_e('Pro','woolentor');?></legend>
                    <?php
                        woocommerce_wp_select([
                            'id'          => 'wl_pro_payment_method_id',
                            'type'        => 'select',
                            'value'       => 'select_payment',
                            'options'     => [
                                'select_payment' => esc_html__( 'Select Payment Method', 'woolentor' ),
                            ],
                            'style'       => 'width:50%;',
                            'label'       => esc_html__( 'Payment methods', 'woolentor' ),
                            'description' => esc_html__( 'The coupon will only be applicable if the selected payment method matches either condition.', 'woolentor' ),
                            'desc_tip'    => true,
                            'custom_attributes' =>[ "disabled" => true ]
                        ]);

                        woocommerce_wp_select([
                            'id'          => 'wl_pro_payment_restrict_type',
                            'type'        => 'select',
                            'value'       => 'allowed',
                            'options'     => [
                                'allowed' => esc_html__( 'Allowed', 'woolentor' ),
                            ],
                            'style'       => 'width:50%;',
                            'label'       => esc_html__( 'Payment restrict type', 'woolentor' ),
                            'description' => esc_html__( 'The type of implementation for this restriction. Select "allowed" to allow coupon only to payment method under the selected method. Select "disallowed" to only allow coupon to payment that don\'t fall under the selected method.', 'woolentor' ),
                            'desc_tip'    => true,
                            'custom_attributes' =>[ "disabled" => true ]
                        ]);

                        woocommerce_wp_textarea_input([
                            'id'          => 'wl_pro_payment_error_message',
                            'label'       => esc_html__( 'Payment error message', 'woolentor' ),
                            'description' => esc_html__( 'Show a personalized error message to customers attempting to use a coupon before it state date.', 'woolentor' ),
                            'desc_tip'    => true,
                            'type'        => 'text',
                            'data_type'   => 'text',
                            'placeholder' => esc_html__('This coupon is not applicable with your selected payment method.','woolentor'),
                            'value'       => '',
                            'custom_attributes' =>[ "disabled" => true ]
                        ]);
                    ?>
                </fieldset>
            </div>
        <?php
    }

    /**
     * URL Pro Field
     * @param mixed $coupon_id
     * @return void
     */
    public function pro_url_option_field( $coupon_id ){
        ?>
            <div class="woolentor-coupon-pro-options options_group" style="border-top:0;">
                <fieldset>
                    <legend><?php esc_html_e('Pro','woolentor');?></legend>
                    <?php

                        woocommerce_wp_checkbox([
                            'id'          => 'woolentor_coupon_url_enable',
                            'value'       => 'no',
                            'label'       => esc_html__( 'Enable URL Coupon', 'woolentor' ),
                            'description' => esc_html__( 'You can enable or disable the coupon URL functionality here.', 'woolentor' ),
                            'desc_tip'    => false,
                            'custom_attributes' =>[ "disabled" => true ]
                        ]);

                        woocommerce_wp_text_input([
                            'id'                => 'woolentor_coupon_url',
                            'label'             => esc_html__( 'Coupon URL', 'woolentor' ),
                            'description'       => esc_html__( 'When visitors use this link, the coupon code will be automatically applied to their cart.', 'woolentor' ),
                            'type'              => 'text',
                            'data_type'         => 'text',
                            'value'             => '',
                            'custom_attributes' => [ 'disabled' => true ],
                            'desc_tip'          => true,
                        ]);

                        woocommerce_wp_text_input([
                            'id'          => 'woolentor_code_change_in_url',
                            'label'       => esc_html__( 'Code Change In URL', 'woolentor' ),
                            'description' => esc_html__( 'You can change the url code to use this field.', 'woolentor' ),
                            'desc_tip'    => true,
                            'type'        => 'text',
                            'data_type'   => 'text',
                            'value'       => '',
                            'custom_attributes' => [ 'disabled' => true ],
                        ]);

                        woocommerce_wp_text_input([
                            'id'          => 'woolentor_coupon_url_redirect_url',
                            'label'       => esc_html__( 'Redirect URL', 'woolentor' ),
                            'description' => esc_html__( 'User redirect URL after apply coupon.', 'woolentor' ),
                            'desc_tip'    => true,
                            'type'        => 'text',
                            'data_type'   => 'text',
                            'value'       => '',
                            'placeholder' => wc_get_cart_url(),
                            'custom_attributes' => [ 'disabled' => true ],
                        ]);

                        woocommerce_wp_textarea_input([
                            'id'          => 'woolentor_coupon_url_success_message',
                            'label'       => esc_html__( 'Apply Success Message', 'woolentor' ),
                            'description' => esc_html__( 'Message that will be displayed when a coupon has been applied successfully. Leave blank to use the default message.', 'woolentor' ),
                            'desc_tip'    => true,
                            'type'        => 'text',
                            'data_type'   => 'text',
                            'placeholder' => esc_html__( 'The coupon was applied successfully.', 'woolentor' ),
                            'value'       => '',
                            'custom_attributes' => [ 'disabled' => true ],
                        ]);

                    ?>
                </fieldset>
            </div>
        <?php
    }

    /**
     * Manage Metaboxes
     * @param mixed $coupon_id
     * @param mixed $coupon
     * @return void
     */
    public function save_meta_boxes_data($coupon_id, $coupon){

        // Check nonce
        if ( empty($_POST['_wpnonce']) || empty($_POST['post_ID']) || 
            !wp_verify_nonce(
                sanitize_text_field(wp_unslash($_POST['_wpnonce'])),
                'update-post_' . sanitize_text_field(wp_unslash($_POST['post_ID']))
            )
        ) {
            return;
        }

        // Start date
        $start_date = ( !empty($_POST['woolentor_start_date'] ) ? sanitize_text_field( wp_unslash($_POST['woolentor_start_date']) ) : '');
        $start_date_err_message = ( !empty($_POST['woolentor_start_date_error_message'] ) ? sanitize_text_field( wp_unslash($_POST['woolentor_start_date_error_message']) ) : '');

        update_post_meta($coupon_id, 'woolentor_start_date', $start_date);
        update_post_meta($coupon_id, 'woolentor_start_date_error_message', $start_date_err_message);

        // Shipping method restric
        $shipping_restrict_type = ( !empty($_POST['woolentor_shipping_restrict_type'] ) ? sanitize_text_field( wp_unslash($_POST['woolentor_shipping_restrict_type']) ) : '');
        $shipping_method_ids = !empty( $_POST['woolentor_shipping_method_ids'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_POST['woolentor_shipping_method_ids'] ) ) : [];
        $shipping_error_msg = ( !empty($_POST['woolentor_shipping_error_message'] ) ? sanitize_text_field( wp_unslash($_POST['woolentor_shipping_error_message']) ) : '');

        update_post_meta($coupon_id, 'woolentor_shipping_restrict_type', $shipping_restrict_type);
        update_post_meta($coupon_id, 'woolentor_shipping_method_ids', $shipping_method_ids);
        update_post_meta($coupon_id, 'woolentor_shipping_error_message', $shipping_error_msg);

        // User role restric
        $user_role_restrict_type = ( !empty($_POST['woolentor_user_role_restrict_type'] ) ? sanitize_text_field( wp_unslash($_POST['woolentor_user_role_restrict_type']) ) : '');
        $user_roles = !empty( $_POST['woolentor_user_roles'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_POST['woolentor_user_roles'] ) ) : [];
        $user_role_error_msg = ( !empty($_POST['woolentor_user_role_error_message'] ) ? sanitize_text_field( wp_unslash($_POST['woolentor_user_role_error_message']) ) : '');

        update_post_meta($coupon_id, 'woolentor_user_role_restrict_type', $user_role_restrict_type);
        update_post_meta($coupon_id, 'woolentor_user_roles', $user_roles);
        update_post_meta($coupon_id, 'woolentor_user_role_error_message', $user_role_error_msg);

        // Country restric
        $checking_address_type = ( !empty($_POST['woolentor_checking_address_type'] ) ? sanitize_text_field( wp_unslash($_POST['woolentor_checking_address_type']) ) : '');
        $country_restrict_type = ( !empty($_POST['woolentor_country_restrict_type'] ) ? sanitize_text_field( wp_unslash($_POST['woolentor_country_restrict_type']) ) : '');
        $countries = !empty( $_POST['woolentor_countries'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_POST['woolentor_countries'] ) ) : [];
        $country_error_msg = ( !empty($_POST['woolentor_country_error_message'] ) ? sanitize_text_field( wp_unslash($_POST['woolentor_country_error_message']) ) : '');

        update_post_meta($coupon_id, 'woolentor_checking_address_type', $checking_address_type);
        update_post_meta($coupon_id, 'woolentor_countries', $countries);
        update_post_meta($coupon_id, 'woolentor_country_restrict_type', $country_restrict_type);
        update_post_meta($coupon_id, 'woolentor_country_error_message', $country_error_msg);

    }

}