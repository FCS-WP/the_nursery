<?php
/**
 * [woolentor_opt_data_clean] clean array data
 *
 * @param [array] $var
 * @return mixed
 */
function woolentor_opt_data_clean( $var ) {
    if ( is_array( $var ) ) {
        return array_map( 'woolentor_opt_data_clean', $var );
    } else {
        return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
    }
}

/**
 * Get Options Value
 *
 * @param [type] $key
 * @param [type] $section
 * @param boolean $default
 * @return mixed
 */
function woolentor_opt_get_option( $key, $section, $default = false ){
    if('woolentor_woo_template_tabs' == $section){
        $value = woolentor_opt_get_template_id($key, $section, $default);
    }else{
        $options = get_option( $section );
        if ( isset( $options[$key] ) ) {
            $value = $options[$key];
        }else{
            $value = $default;
        }
    }
    
    return apply_filters( 'woolentor_opt' . '_get_option_' . $key, $value, $key, $default );
}

/**
 * Template ID Fetching Language Wise
 * @param mixed $template_key
 * @param mixed $section
 * @param mixed $callback
 * @return mixed
 */
function woolentor_opt_get_template_id( $template_key, $section, $default = false, $callback = false ){

    $language_code = \WooLentor\MultiLanguage\Languages::$language_code;

    $option_value = ( $callback && is_callable( $callback ) ) ? $callback( $template_key, $section, '0' ) : woolentor_get_option( $template_key, $section, '0' );
    $option_value = maybe_unserialize( $option_value );
    $template_id = $default;
    if( is_array( $option_value ) && array_key_exists( $language_code, $option_value['lang'] ) ){
        $template_id = ( $option_value['lang'][$language_code]['template_id'] != '0' ) ? $option_value['lang'][$language_code]['template_id'] : $option_value['lang']['en']['template_id'];
    }else{
        if( is_array( $option_value ) ){
            $template_id = isset( $option_value['lang']['en']['template_id'] ) ? $option_value['lang']['en']['template_id'] : '0';
        }else{
            $template_id = $option_value;
        }
    }
    return $template_id != '0' ? $template_id : $default;
}

/**
 * Get all option values from registered settings
 *
 * @param array $registered_settings Array of registered settings
 * @return array|void Settings array or void if input is invalid
 */
function woolentor_opt_get_options( $registered_settings = [] ) {
    // Validate input
    if( ! is_array( $registered_settings ) ) {
        return;
    }

    $settings = [];

    // Process each settings section
    foreach ( $registered_settings as $section_key => $setting_section ) {
        $section_options = [];

        foreach ( $setting_section as $setting ) {

            // Skip non-data fields
            if( in_array( $setting['type'], ['title', 'html'], true ) ) {
                continue;
            }

            // Handle sub-sections
            if( isset( $setting['section'] ) ) {
                $sub_options = woolentor_process_sub_section_fields( $setting );
                if( !empty( $sub_options ) ) {
                    $settings[$setting['section']] = $sub_options;
                }
                continue;
            }

            if( !isset( $setting['id'] ) ) {
                continue;
            }

            // Handle main section fields
            $section_options[ $setting['id'] ] = woolentor_get_field_value( $setting, $section_key );
        }

        if( !empty( $section_options ) ) {
            $settings[$section_key] = $section_options;
        }
    }

    return apply_filters( 'woolentor_opt_get_settings', $settings );
}

/**
 * Process sub-section fields
 * 
 * @param array $setting Setting array containing sub-section fields
 * @return array Processed sub-section options
 */
function woolentor_process_sub_section_fields( $setting ) {
    $sub_options = [];

    foreach ( $setting['setting_fields'] as $sub_setting ) {
        // Skip non-data fields
        if( in_array( $sub_setting['type'], ['title', 'html'], true ) ) {
            continue;
        }

        if( !isset( $sub_setting['id'] ) ) {
            continue;
        }

        $sub_options[ $sub_setting['id'] ] = woolentor_get_field_value( $sub_setting, $setting['section'] );
    }

    return $sub_options;
}

/**
 * Get field value with default fallback
 * 
 * @param array $field Field configuration array
 * @param string $section_key Section identifier
 * @return mixed Field value
 */
function woolentor_get_field_value( $field, $section_key ) {
    $default = isset( $field['std'] ) ? $field['std'] : ( isset( $field['default'] ) ? $field['default'] : '' );
    return woolentor_opt_get_option( $field['id'], $section_key, $default );
}


/**
 * Get Option value Section wise
 * @param mixed $section_key
 * @param mixed $registered_settings
 * @return mixed
 */

function woolentor_opt_get_options_value_by_section( $section_key, $registered_settings = [] ){

    if( ! is_array( $registered_settings ) ){
        return;
    }

    $options = [];
    foreach ( $registered_settings as $setting ) {

        if( isset($setting['parent_id']) ){

            $default = isset( $setting['std'] ) ? $setting['std'] : ( isset( $setting['default'] ) ? $setting['default'] : '' );
            $options[ $setting['id'] ] = woolentor_opt_get_option( $setting['id'], $section_key, $default );

            foreach ($setting['setting_fields'] as $sub_setting) {
                $default = isset( $sub_setting['std'] ) ? $sub_setting['std'] : ( isset( $sub_setting['default'] ) ? $sub_setting['default'] : '' );
                $options[ $sub_setting['id'] ] = woolentor_opt_get_option( $sub_setting['id'], $setting['parent_id'], $default );
            }

        }else{
            $default                   = isset( $setting['std'] ) ? $setting['std'] : ( isset( $setting['default'] ) ? $setting['default'] : '' );
            $options[ $setting['id'] ] = woolentor_opt_get_option( $setting['id'], $section_key, $default );
        }

    }

    return apply_filters( 'woolentor_opt_get_settings_'.$section_key, $options );
}

/**
 * Load Template
 * @param mixed $templates
 * @return void
 */
function woolentor_opt_load_template( $template ) {
    $tmp_file = WOOLENTOROPT_INCLUDES . '/templates/dashboard-' . $template . '.php';
    if ( file_exists( $tmp_file ) ) {
        include_once( $tmp_file );
    }
}