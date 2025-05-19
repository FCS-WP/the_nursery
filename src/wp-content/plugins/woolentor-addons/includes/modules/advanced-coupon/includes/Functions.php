<?php
namespace Woolentor\Modules\AdvancedCoupon;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Functions{

    /**
     * Get Meta data
     * @param mixed $id
     * @param mixed $meta_key
     * @return mixed
     */
    public static function get_meta_data($id, $meta_key, $default = ""){
        if (!metadata_exists('post', $id, $meta_key)) {
            return $default;
        }else{
            $meta_data = get_post_meta($id, $meta_key, true);
            return $meta_data;
        }
    }

    /**
     * Multiple Data fetching
     * @param mixed $id
     * @param mixed $meta_key
     * @param mixed $default
     * @return array
     */
    public static function get_multiple_meta_date($id, $meta_key, $default = ''){
        $meta_data = self::get_meta_data($id, $meta_key, $default);
        $meta_data = (is_string($meta_data) && $meta_data ? array_filter(explode(',', $meta_data)) : $meta_data);
        
        return (!is_array($meta_data) ? [] : $meta_data);
    }

    /**
     * Creates a WC_DateTime object from various date inputs (timestamp, string, or WC_DateTime object).
     *
     * @param mixed $date The input date, which can be a timestamp, a date string, or a WC_DateTime object.
     * @return \WC_DateTime|null A WC_DateTime object on success, or null on failure.
     */
    public static function create_datetime($date) {
        try {
            if (empty($date)) {
                return null;
            }
    
            // If it's already a WC_DateTime object, return it directly.
            if ($date instanceof \WC_DateTime) {
                return $date;
            }
    
            if (is_numeric($date)) {
                // Handle numeric timestamps (UTC).
                $datetime = new \WC_DateTime("@{$date}", new \DateTimeZone('UTC'));
            } else {
                // Handle string-based dates.
                $timestamp = self::parse_date_to_timestamp($date);
                if ($timestamp === false) {
                    return null; // Invalid date string, return null.
                }
                $datetime = new \WC_DateTime("@{$timestamp}", new \DateTimeZone('UTC'));
            }
    
            // Apply local timezone settings.
            if ( get_option('timezone_string') ) {
                $datetime->setTimezone(new \DateTimeZone(wc_timezone_string()));
            } else {
                $datetime->set_utc_offset(wc_timezone_offset());
            }
    
            return $datetime;
        } catch (\Exception $e) {
            // Suppress exception and return null on failure.
            return null;
        }
    }
    
    /**
     * Parse a date string into a timestamp.
     *
     * @param string $date The date string.
     * @return int|false The timestamp, or false on failure.
     */
    private static function parse_date_to_timestamp($date) {
        // Check for ISO8601 format and extract the timestamp.
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(Z|([-+]\d{2}:\d{2}))?$/', $date, $matches)) {
            $offset = !empty($matches[7]) 
                ? iso8601_timezone_to_offset($matches[7]) 
                : wc_timezone_offset();
    
            return gmmktime(
                $matches[4], $matches[5], $matches[6], 
                $matches[2], $matches[3], $matches[1]
            ) - $offset;
        }
    
        // Handle other string formats using WooCommerce helper.
        $gmt_date = get_gmt_from_date(gmdate('Y-m-d H:i:s', wc_string_to_timestamp($date)));
        return wc_string_to_timestamp($gmt_date);
        
    }

    /**
     * Prepares a timestamp from a WC_DateTime object.
     * @param mixed $date The input date, which can be a WC_DateTime object, timestamp, or string.
     * @param bool $gmt Optional. Whether to return the GMT timestamp. Default: true.
     * @return int The corresponding timestamp, or 0 if the date is invalid.
     */
    public static function get_date_timestamp($date, $gmt = true) {
        // Convert the input date to a WC_DateTime object.
        $datetime = self::create_datetime($date);

        // If the conversion was successful, return the appropriate timestamp.
        if ($datetime instanceof \WC_DateTime) {
            return $gmt ? $datetime->getOffsetTimestamp() : $datetime->getTimestamp();
        }

        // Return 0 if the input was invalid or conversion failed.
        return 0;
    }

    /**
     * Generate Coupon URL from coupon id
     * @param mixed $coupon_id
     * @return array|string
     */
    public static function generate_coupon_url( $coupon_id ){

        if ( get_post_status( $coupon_id ) === 'auto-draft' ) {
            return '';
        }

        $coupon_obj = new \WC_Coupon($coupon_id);

        $coupon_permalink = get_permalink( $coupon_id, true );
        $custom_code      = self::get_meta_data($coupon_id,'woolentor_code_change_in_url');
        $coupon_code      = !empty( $custom_code ) ? $custom_code : $coupon_obj->get_code();

        // Replace any colon (:) with %3A and any comma (,) with %2C in the coupon code to ensure it is URL-safe
        $slug = str_replace( [':', ','], ['%3A', '%2C'], $coupon_code );

        // Generate permalink.
        $coupon_permalink = str_replace( '%shop_coupon%', $slug, $coupon_permalink );

        return $coupon_permalink;

    }

    /**
     * Get Coupon ID
     * @param mixed $coupon_slug
     * @return int|mixed
     */
    public static function get_coupon_id_by_slug( $coupon_slug ) {
        $post      = $coupon_slug ? get_page_by_path( $coupon_slug, OBJECT, 'shop_coupon' ) : null;
        $coupon_id = $post ? $post->ID : 0;

        // Checked if custom coupon code is Exist in the meta value and coupon id not found.
        if ( $coupon_slug && !$coupon_id ) {
            $coupon_id = self::get_coupon_id_by_custom_slug( $coupon_slug );
        }

        return $coupon_id;
    }

    /**
     * Get Coupon ID from custom slug
     * @param mixed $coupon_slug
     * @return int
     */
    public static function get_coupon_id_by_custom_slug( $coupon_slug ){
        $args = [
            'post_type'      => 'shop_coupon',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'     => 'woolentor_code_change_in_url',
                    'value'   => $coupon_slug,
                    'compare' => '=',
                ],
            ],
            'fields' => 'ids', // return only the post ID
        ];
        
        $coupon_ids = get_posts($args);
        
        if (!empty($coupon_ids)) {
            $coupon_id = absint($coupon_ids[0]);
        } else {
            $coupon_id = 0;
        }

        return $coupon_id;
    }

    /**
     * Get WooCommerce Error Message
     * @return mixed
     */
    public static function get_error_message() {
        $error_message = wc_get_notices( 'error' );
        $notice        = is_array( $error_message ) && ! empty( $error_message ) ? end( $error_message ) : null;

        if ( is_array( $notice ) ) {
            return $notice['notice'];
        } else {
            return $notice ? $notice : '';
        }
        
    }

    /**
     * Generate String
     * @param mixed $length
     * @return string
     */
    public static function generate_string($length = 5) {
        $character_set = 'abcdefghijklmnopqrstuvwxyz0123456789';
        
        do {
            $generate_string = substr(str_shuffle(str_repeat($character_set, $length)), 0, $length);
        } while (wc_get_coupon_id_by_code($generate_string));
    
        return $generate_string;
    }


}