<?php
namespace WoolentorOptions\Api;

use WP_REST_Controller;
use WP_Error;

/**
 * Custom Actions Handler
 */
class Custom_Actions extends WP_REST_Controller {

    /**
     * Constructor
     */
    public function __construct() {
        $this->namespace = 'woolentoropt/v1';
        $this->rest_base = 'custom-action';
    }

    /**
     * Register Routes
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => [$this, 'handle_action'],
                    'permission_callback' => [$this, 'permissions_check'],
                    'args'                => $this->get_collection_params(),
                ]
            ]
        );
    }

    /**
     * Permission Check
     */
    public function permissions_check($request) {
        if (!current_user_can('manage_options')) {
            return new WP_Error(
                'rest_forbidden',
                esc_html__('You do not have permissions to manage this resource.', 'woolentor'),
                ['status' => 401]
            );
        }
        return true;
    }

    /**
     * Get collection parameters
     */
    public function get_collection_params() {
        return [
            'callback' => [
                'required' => true,
                'type'    => 'string',
            ],
            'field_id' => [
                'required' => true,
                'type'    => 'string',
            ],
            'data' => [
                'required' => false,
                'type'    => 'object',
            ],
        ];
    }

    /**
     * Handle Custom Action
     */
    public function handle_action($request) {
        // Get request parameters
        $callback = sanitize_text_field($request['callback']);
        $field_id = sanitize_text_field($request['field_id']);
        $data     = $request['data'] ? $request['data'] : [];

        // Verify nonce
        if (!wp_verify_nonce($data['nonce'], 'woolentor_verifynonce')) {
            return new WP_Error(
                'invalid_nonce',
                __('Invalid nonce', 'woolentor'),
                ['status' => 403]
            );
        }

        try {
            // Check if callback exists and is callable
            if (!is_callable($callback)) {
                throw new \Exception(__('Invalid callback function', 'woolentor'));
            }

            // Execute the callback

            $dependent_value = !empty( $data['depend_value'] ) ? $data['depend_value'] : '';
            $result = call_user_func( $callback, [ 'field_id' => $field_id, 'depend_value' => $dependent_value, 'data' => $data ] );

            // Return success response
            return rest_ensure_response([
                'success' => true,
                'message' => __('Action completed successfully', 'woolentor'),
                'data'    => $result
            ]);

        } catch (\Exception $e) {
            return new WP_Error(
                'action_failed',
                $e->getMessage(),
                ['status' => 500]
            );
        }
    }
}