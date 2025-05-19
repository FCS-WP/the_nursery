<?php
namespace Woolentor\Modules\AdvancedCoupon\Frontend;
use WooLentor\Traits\Singleton;
use Woolentor\Modules\AdvancedCoupon\Functions;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Coupon_Rule_Checker {
    use Singleton;

    public function __construct(){
        add_filter('woocommerce_coupon_is_valid', [$this,'woocommerce_coupon_is_valid'], 10, 2);
    }

    /**
     * Check Coupon Validility
     * @param mixed $is_valid
     * @param mixed $coupon
     * @return mixed
     */
    public function woocommerce_coupon_is_valid($is_valid, $coupon) {
        $coupon_id = $coupon->get_id();

        // Starting date validation
        $is_valid = $this->checked_starting_date($coupon_id, $is_valid);

        // Shipping Method
        if( $is_valid ){
            $is_valid = $this->checked_shipping_method($coupon_id, $is_valid);
        }

        // User Role
        if( $is_valid ){
            $is_valid = $this->checked_user_role($coupon_id, $is_valid);
        }

        // Country
        if( $is_valid ){
            $is_valid = $this->checked_country($coupon_id, $is_valid);
        }
    
        return $is_valid;
    }

    /**
     * Starting date validation
     * @param mixed $coupon_id
     * @param mixed $is_valid
     * @throws \Exception
     * @return mixed
     */
    public function checked_starting_date( $coupon_id, $is_valid ){
        $start_date         = Functions::get_meta_data($coupon_id, 'woolentor_start_date');
        $start_date_err_msg = Functions::get_meta_data($coupon_id, 'woolentor_start_date_error_message');
        $start_date_err_msg = !empty($start_date_err_msg) ? $start_date_err_msg : 'This coupon will be valid after starting date. Please try again later.';

        // If the start date is empty, return the current validity status.
        if ( empty($start_date) ) {
            return $is_valid;
        }

        $start_date_timestamp = Functions::get_date_timestamp($start_date, false);
    
        // If the coupon's start date is in the future, invalidate the coupon and throw an exception.
        if ($start_date_timestamp > time()) {
            $is_valid = false;
            throw new \Exception( esc_html( $start_date_err_msg ), 109 );
        }

        return $is_valid;

    }

    /**
     * Checked Shipping Method condition
     * @param mixed $coupon_id
     * @param mixed $is_valid
     * @throws \Exception
     * @return mixed
     */
    public function checked_shipping_method($coupon_id, $is_valid){

        $shipping_method_ids    = Functions::get_multiple_meta_date($coupon_id, 'woolentor_shipping_method_ids');
        $shipping_restrict_type = Functions::get_meta_data( $coupon_id , 'woolentor_shipping_restrict_type');
        $shipping_err_msg       = Functions::get_meta_data($coupon_id, 'woolentor_shipping_error_message');
        $shipping_err_msg       = !empty($shipping_err_msg) ? $shipping_err_msg : 'This coupon is not applicable with your selected shipping method.';

        // If the shipping method is empty, return the current validity status.
        if ( empty($shipping_method_ids) ) {
            return $is_valid;
        }

        if( !empty( $shipping_method_ids ) ){
            $woocommerce   = WC();
            $chosen_method = isset( $woocommerce->session->chosen_shipping_methods[0] ) ? $woocommerce->session->chosen_shipping_methods[0] : '';

            if (strpos($chosen_method, ":") !== false) {
                $chosen_method = strstr($chosen_method, ":", true);
            }

            // Determine the validity condition based on the restrict type
            $is_method_restricted = in_array($chosen_method, $shipping_method_ids);
            $validity_condition = ($shipping_restrict_type === 'allowed') ? !$is_method_restricted : $is_method_restricted;

            // Set $is_valid to false and throw an exception if the condition is not met
            if ( $validity_condition ){
                $is_valid = false;
                throw new \Exception( esc_html( $shipping_err_msg ), 109 );
            }

        }

        return $is_valid;
    }


    /**
     * Checked User Role
     * @param mixed $coupon_id
     * @param mixed $is_valid
     * @throws \Exception
     * @return mixed
     */
    public function checked_user_role($coupon_id, $is_valid){
        $current_user = wp_get_current_user();

        // Retrieve meta data for the coupon
        $user_roles         = Functions::get_multiple_meta_date($coupon_id, 'woolentor_user_roles');
        $role_restrict_type = Functions::get_meta_data($coupon_id, 'woolentor_user_role_restrict_type');
        $role_err_msg       = Functions::get_meta_data($coupon_id, 'woolentor_user_role_error_message');
        $role_err_msg       = !empty($role_err_msg) ? $role_err_msg : 'You are not eligible to use this coupon.';

        // If the user role is empty, return the current validity status.
        if ( empty($user_roles) ) {
            return $is_valid;
        }

        // Determine current user roles or assign 'guest' if the user is not logged in
        $current_user_roles = $current_user->ID ? $current_user->roles : ['guest'];
        
        // Check if the user has any intersecting roles
        $has_matching_roles = !empty(array_intersect($current_user_roles, $user_roles));

        // Determine the validity condition based on the restrict type
        $validity_condition = ($role_restrict_type === 'allowed') ? !$has_matching_roles : $has_matching_roles;

        // Throw an exception if the coupon is not valid for the user role
        if ($validity_condition) {
            $is_valid = false;
            throw new \Exception( esc_html( $role_err_msg ), 109 );
        }

        return $is_valid;
    }


    /**
     * Checked Country
     * @param mixed $coupon_id
     * @param mixed $is_valid
     * @throws \Exception
     * @return mixed
     */
    public function checked_country($coupon_id, $is_valid){
        $woocommerce = WC();
        $session     = $woocommerce->session;

        // Retrieve meta data for the coupon
        $countries             = Functions::get_multiple_meta_date($coupon_id, 'woolentor_countries');
        $checking_address_type = Functions::get_meta_data($coupon_id, 'woolentor_checking_address_type');
        $country_restrict_type = Functions::get_meta_data($coupon_id, 'woolentor_country_restrict_type');
        $country_err_msg       = Functions::get_meta_data($coupon_id, 'woolentor_country_error_message');
        $country_err_msg       = !empty($country_err_msg) ? $country_err_msg : 'This coupon is not applicable with your selected country.';

        // If the country is empty, return the current validity status.
        if ( empty($countries) ) {
            return $is_valid;
        }

        // Check for valid session and countries
        if (!empty($countries) && $session !== null) {
            $choosed_country = ($checking_address_type === 'billing') ? $session->customer['country'] : $session->customer['shipping_country'];

            // Determine validity based on restrict type
            $is_country_restricted = in_array($choosed_country, $countries);
            $validity_condition = ($country_restrict_type === 'allowed') ? !$is_country_restricted : $is_country_restricted;

            // Throw exception if the condition fails
            if ($validity_condition) {
                $is_valid = false;
                throw new \Exception(esc_html($country_err_msg), 109);
            }
        }

        return $is_valid;
    }

    

}