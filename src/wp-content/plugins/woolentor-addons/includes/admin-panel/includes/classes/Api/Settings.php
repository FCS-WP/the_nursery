<?php
namespace WoolentorOptions\Api;

use WP_REST_Controller;
use WoolentorOptions\SanitizeTrail\Sanitize_Trait;

if ( !class_exists( '\WoolentorOptions\Admin\Options_Field'  ) ) {
    require_once WOOLENTOROPT_INCLUDES . '/classes/Admin/Options_field.php';
}

// Load If Pro Active
if( woolentor_is_pro() && defined( "WOOLENTOR_ADDONS_PL_PATH_PRO" ) && file_exists( WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/admin/admin_fields.php' ) ){
    require_once WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/admin/admin_fields.php';
}

/**
 * REST_API Handler
 */
class Settings extends WP_REST_Controller {

    use Sanitize_Trait;

    protected $namespace;
    protected $rest_base;
    protected $slug;
    protected $errors;

    /**
	 * All registered settings.
	 *
	 * @var array
	 */
	protected $settings;

    /**
     * [__construct Settings constructor]
     */
    public function __construct() {
        $this->slug      = 'woolentor_';
        $this->namespace = 'woolentoropt/v1';
        $this->rest_base = 'settings';
        $this->settings  = \WoolentorOptions\Admin\Options_Field::instance()->get_registered_settings();
        $this->errors    = new \WP_Error();

        add_filter( $this->slug . '_settings_sanitize', [ $this, 'sanitize_settings' ], 3, 10 );

    }

    /**
     * Register the routes
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/'.$this->rest_base,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, 'permissions_check' ],
                    'args'                => $this->get_collection_params(),
                ],

                [
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_items' ],
                    'permission_callback' => [ $this, 'permissions_check' ],
                    'args'                => $this->get_collection_params(),
                ]
            ]
        );

        register_rest_route(
            $this->namespace,
            '/'.$this->rest_base.'/dependelement',
            [
                [
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'depend_element_settings' ],
                    'permission_callback' => [ $this, 'permissions_check' ],
                ]
            ]
        );

    }

    /**
     * Checks if a given request has access to read the items.
     *
     * @param \WP_REST_Request $request Full details about the request.
     *
     * @return true|\WP_Error True if the request has read access, WP_Error object otherwise.
     */
    public function permissions_check( $request ) {

        if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error( 'rest_forbidden', 'WOOLENTOR OPT: Permission Denied.', [ 'status' => 401 ] );
		}

		return true;
    }

    /**
     * Retrieves the query params for the items collection.
     *
     * @return array Collection parameters.
     */
    public function get_collection_params() {
        return [];
    }

    /**
     * Retrieves a collection of items.
     *
     * @param \WP_REST_Request $request Full details about the request.
     *
     * @return \WP_REST_Response|\WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_items( $request ) {
        $items = [];

        $nonce = $request->get_param('nonce');
        if ( ! wp_verify_nonce( $nonce, 'woolentor_verifynonce' ) ) {
            return new \WP_Error('rest_forbidden', __('Nonce not verified.'), ['status' => 403]);
        }

        $section = (string) $request['section'];
        if( !empty( $section ) ){
            $items = $this->get_options_value( $section );
        }
        
        $response = rest_ensure_response( $items );
        return $response;
    }

    public function get_options_value( $section ) {

        $registered_settings = !empty($section) && isset($this->settings[$section]) ? $this->settings[$section] : [];
        $options = woolentor_opt_get_options_value_by_section($section, $registered_settings);
        return $options;
        
    }

    /**
     * Create item response
     */
    public function create_items( $request ) {

        if ( ! wp_verify_nonce( $request['settings']['verifynonce'], 'woolentor_verifynonce' ) ) {
            return new \WP_Error('rest_forbidden', __('Nonce not verified.'), ['status' => 403]);
        }

        $section            = ( !empty( $request['section'] ) ? sanitize_text_field( $request['section'] ) : '' );
        $sub_section        = ( !empty( $request['subsection'] ) ? sanitize_text_field( $request['subsection'] ) : '' );
        $settings_received  = ( !empty( $request['settings'] ) ? woolentor_opt_data_clean( $request['settings'] ) : '' );
        $settings_reset     = ( !empty( $request['reset'] ) ? rest_sanitize_boolean( $request['reset'] ) : '' );

        // Data reset
        if( $settings_reset == true ){

            if( !empty( $sub_section ) ) {
                $reseted = delete_option( $sub_section );
            } else{
                $reseted = delete_option( $section );
            }
            
            return rest_ensure_response( $reseted );
        }

        if( empty( $section ) || empty( $settings_received ) ){
            return;
        }

        $registered_settings = !empty($sub_section) ? $this->settings[$section][$this->get_section_index( $section, $sub_section )]['setting_fields'] : $this->settings[$section]; // If sub section is not empty, get the sub section settings.
        $data_to_save        = [];

        $existing_data = !empty($sub_section) ? get_option( $sub_section, [] ) : get_option( $section, [] );

        if ( is_array( $registered_settings ) && ! empty( $registered_settings ) ) {
			foreach ( $registered_settings as $setting ) {

                // Skip if no setting type.
                if ( ! $setting['type'] ) {
                    continue;
                }

                // Skip if setting type is html.
                if ( $setting['type'] === 'html' || $setting['type'] === 'title' ) {
                    continue;
                }

                if ( isset( $setting['is_pro'] ) && $setting['is_pro'] ) {
                    continue;
                }

                // Skip if the ID doesn't exist in the data received.
                if ( ! array_key_exists( $setting['id'], $settings_received ) ) {
                    continue;
                }

                // Sanitize the input.
                $setting_type = $setting['type'];
                $output       = apply_filters( $this->slug . '_settings_sanitize', $settings_received[ $setting['id'] ], $this->errors, $setting );
                $output       = apply_filters( $this->slug . '_settings_sanitize_' . $setting['id'], $output, $this->errors, $setting );

                if ( $setting_type == 'checkbox' && $output == false ) {
                    continue;
                }

                // Add the option to the list of ones that we need to save.
                if ( ! is_wp_error( $output ) ) {
                    $existing_data[ $setting['id'] ] = $output;
                }
                // if ( ! empty( $output ) && ! is_wp_error( $output ) ) {
                //     $existing_data[ $setting['id'] ] = $output;
                // }

            }
        }

        if ( ! empty( $this->errors->get_error_codes() ) ) {
			return new \WP_REST_Response( $this->errors, 422 );
		}

        if( ! empty( $sub_section ) ){
		    update_option( $sub_section, $existing_data );
            
        } else {
            update_option( $section, $existing_data );
        }

		return rest_ensure_response( $existing_data );
        
    }

    /**
     * Element dependency settings Field but data save under parent section.
     * @param mixed $request
     */
    public function depend_element_settings( $request ){
        if ( ! wp_verify_nonce( $request['settings']['verifynonce'], 'woolentor_verifynonce' ) ) {
            return new \WP_Error('rest_forbidden', __('Nonce not verified.'), ['status' => 403]);
        }

        $settings_received  = ( !empty( $request['settings'] ) ? woolentor_opt_data_clean( $request['settings'] ) : '' );
        $section            = ( !empty( $request['section'] ) ? sanitize_text_field( $request['section'] ) : '' );
        $detect_id          = ( !empty( $settings_received['detectId'] ) ? sanitize_text_field( $settings_received['detectId'] ) : '' );

        $registered_settings = !empty($section) ? $this->settings[$section][$this->get_section_index( $section, $section, 'parent_id', $detect_id )]['setting_fields'] : $this->settings[$section];

        $existing_data = get_option( $section, [] );

        if ( is_array( $registered_settings ) && ! empty( $registered_settings ) ) {

			foreach ( $registered_settings as $setting ) {

                // Skip if no setting type.
                if ( ! $setting['type'] ) {
                    continue;
                }

                // Skip non-data fields
                if( in_array( $setting['type'], ['title', 'html'], true ) ) {
                    continue;
                }

                // Skip if pro field
                if ( isset( $setting['is_pro'] ) && $setting['is_pro'] ) {
                    continue;
                }

                // Skip if the ID doesn't exist in the data received.
                if ( ! array_key_exists( $setting['id'], $settings_received ) ) {
                    continue;
                }

                // Sanitize the input.
                $setting_type = $setting['type'];
                $output       = apply_filters( $this->slug . '_settings_sanitize', $settings_received[ $setting['id'] ], $this->errors, $setting );
                $output       = apply_filters( $this->slug . '_settings_sanitize_' . $setting['id'], $output, $this->errors, $setting );

                if ( $setting_type == 'checkbox' && $output == false ) {
                    continue;
                }

                // Add the option to the list of ones that we need to save.
                if ( ! empty( $output ) && ! is_wp_error( $output ) ) {
                    $existing_data[ $setting['id'] ] = $output;
                }

            }
        }

        if ( ! empty( $this->errors->get_error_codes() ) ) {
			return new \WP_REST_Response( $this->errors, 422 );
		}

        update_option( $section, $existing_data );

		return rest_ensure_response( $existing_data );

    }

    /**
     * Find Subsection index
     * @param mixed $section
     * @param mixed $find_section
     * @return mixed
     */
    public function get_section_index( $section, $find_section, $find_key = 'section', $field_id = '' ){
        // Get all settings fields
        $all_fields = $this->settings;
    
        // Look through others tab fields
        if( isset( $all_fields[$section] ) ){
            foreach( $all_fields[$section] as $index => $field ){
                if( !empty( $field_id ) ){
                    if( isset( $field[$find_key] ) && $field[$find_key] === $find_section && $field['id'] == $field_id ){
                        return $index;
                    }
                }else{
                    if( isset( $field[$find_key] ) && $field[$find_key] === $find_section ){
                        return $index;
                    }
                }
            }
        }
    
        return -1;
    }

    /**
     * Sanitize callback for Settings Data
     *
     * @return mixed
     */
    public function sanitize_settings( $setting_value, $errors, $setting ){

        if ( ! empty( $setting['sanitize_callback'] ) && is_callable( $setting['sanitize_callback'] ) ) {
            $setting_value = call_user_func( $setting['sanitize_callback'], $setting_value );
        } else {
            $setting_value = $this->default_sanitizer( $setting_value, $errors, $setting );
        }

        return $setting_value;

    }

    /**
     * If no Sanitize callback function from option field.
     *
     * @return mixed
     */
    public function default_sanitizer( $setting_value, $errors, $setting ){

        switch ( $setting['type'] ) {
            case 'text':
            case 'radio':
            case 'select':
                $finalvalue = $this->sanitize_text_field( $setting_value, $errors, $setting );
                break;

            case 'textarea':
                $finalvalue = $this->sanitize_textarea_field( $setting_value, $errors, $setting );
                break;

            case 'checkbox':
            case 'switcher':
                $finalvalue = $this->sanitize_checkbox_field( $setting_value, $errors, $setting );
                break;
            
            case 'element':
                $finalvalue = $this->sanitize_element_field( $setting_value, $errors, $setting );
                break;

            case 'multiselect':
            case 'multicheckbox':
                $finalvalue = $this->sanitize_multiple_field( $setting_value, $errors, $setting );
                break;

            case 'file':
                $finalvalue = $this->sanitize_file_field( $setting_value, $errors, $setting );
                break;
            
            case 'repeater':
                $finalvalue = $this->sanitize_repeater_field( $setting_value, $errors, $setting );
                break;

            case 'shortable':
                $finalvalue = $this->sanitize_shortable_field( $setting_value, $errors, $setting );
                break;

            case 'dimensions':
                $finalvalue = $this->sanitize_dimensions_field( $setting_value, $errors, $setting );
                break;
            
            default:
                $finalvalue = sanitize_text_field( $setting_value );
                break;
        }

        return $finalvalue;

    }

}