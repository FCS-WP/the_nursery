<?php
namespace WoolentorOptions\SanitizeTrail;

/**
 * Settings Fields Sanitize handler trait
 */
trait Sanitize_Trait {

    /**
	 * Sanitize the text field.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return string
	 */
	public function sanitize_text_field( $setting_value, $errors, $setting ) {
		return trim( wp_strip_all_tags( $setting_value, true ) );
	}

	/**
	 * Sanitize textarea field.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return string
	 */
	public function sanitize_textarea_field( $setting_value, $errors, $setting ) {
		return stripslashes( wp_kses_post( $setting_value ) );
	}

	/**
	 * Sanitize multiselect and multicheck field.
	 *
	 * @param mixed $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return array
	 */
	public function sanitize_multiple_field( $setting_value, $errors, $setting ) {

		$new_values = [];

		if ( is_array( $setting_value ) && ! empty( $setting_value ) ) {
			foreach ( $setting_value as $key => $value ) {
				$new_values[ sanitize_key( $key ) ] = sanitize_text_field( $value );
			}
		}

		if ( ! empty( $setting_value ) && ! is_array( $setting_value ) ) {
			$setting_value = explode( ',', $setting_value );
			foreach ( $setting_value as $key => $value ) {
				$new_values[ sanitize_key( $key ) ] = sanitize_text_field( $value );
			}
		}

		return $new_values;

	}

	/**
	 * Sanitize urls for the file field.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return string
	 */
	public function sanitize_file_field( $setting_value, $errors, $setting ) {
		return esc_url( $setting_value );
	}

	/**
	 * Sanitize the checkbox field.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return string
	 */
	public function sanitize_checkbox_field( $setting_value, $errors, $setting ) {

		return ( isset( $setting_value ) && 'on' == $setting_value ) ? 'on' : 'off';

	}

	/**
	 * Sanitize element field data.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return string
	 */
	public function sanitize_element_field( $setting_value, $errors, $setting ) {
		return $setting_value;
	}

	/**
	 * Sanitize shortable field data.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return string
	 */
	public function sanitize_shortable_field( $setting_value, $errors, $setting ) {
		return $setting_value;
	}
	
	/**
	 * Sanitize dimensions field data.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return string
	 */
	public function sanitize_dimensions_field( $setting_value, $errors, $setting ) {
		return $setting_value;
	}

	/**
	 * Sanitize repeater field data recursively
	 * 
	 * @param array $data The repeater field data to sanitize
	 * @param object $errors WP_Error object
	 * @param array $setting Field settings
	 * @return array Sanitized data
	 */
	public function sanitize_repeater_field($data, $errors, $setting) {
		// Return empty array if input is not an array
		if (!is_array($data)) {
			return [];
		}

		return array_map(function($item) {
			return $this->sanitize_repeater_item($item);
		}, $data);
	}

	/**
	 * Recursively sanitize a single repeater item
	 *
	 * @param mixed $item The item to sanitize
	 * @return mixed Sanitized item
	 */
	private function sanitize_repeater_item($item) {
		// Return as-is if not an array
		if (!is_array($item)) {
			return $this->sanitize_single_value($item);
		}

		$sanitized_item = [];

		foreach ($item as $key => $value) {
			// Sanitize array key
			$key = sanitize_key($key);

			// Special handling for IDs
			if ($key === 'id') {
				$sanitized_item[$key] = absint($value);
				continue;
			}

			// Recursively sanitize arrays
			if (is_array($value)) {
				$sanitized_item[$key] = $this->sanitize_repeater_item($value);
				continue;
			}

			// Sanitize single value
			$sanitized_item[$key] = $this->sanitize_single_value($value);
		}

		return $sanitized_item;
	}

	/**
	 * Sanitize a single value based on its type
	 * 
	 * @param mixed $value The value to sanitize
	 * @return mixed Sanitized value
	 */
	private function sanitize_single_value($value) {
		// Handle different value types
		switch (true) {
			case is_bool($value):
				return (bool) $value;

			case is_numeric($value):
				return floatval($value);

			case is_string($value):
				// Handle hex color values
				if (strpos($value, '#') === 0) {
					return sanitize_hex_color($value);
				}
				
				// Handle URLs
				if (filter_var($value, FILTER_VALIDATE_URL)) {
					return esc_url_raw($value);
				}
				
				// Handle email addresses
				if (is_email($value)) {
					return sanitize_email($value);
				}
				
				// Default string sanitization
				return sanitize_text_field($value);

			default:
				return '';
		}
	}

}