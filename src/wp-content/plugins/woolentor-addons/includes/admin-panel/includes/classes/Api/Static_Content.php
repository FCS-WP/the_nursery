<?php
namespace WoolentorOptions\Api;

use WP_REST_Controller;

/**
 * REST_API Handler
 */
class Static_Content extends WP_REST_Controller {
    protected $namespace;

    /**
     * [__construct Settings constructor]
     */
    public function __construct() {
        $this->namespace = 'woolentoropt/v1';

    }

    public function register_routes() {

        // Siberbar Content
        register_rest_route(
            $this->namespace,
            '/sidebar-content',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_sidebar_html' ],
                    'permission_callback' => [ $this, 'permissions_check' ],
                ]
            ]
        );

        // Free VS Pro
        register_rest_route(
            $this->namespace,
            '/free-vs-pro',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_free_vs_pro_html' ],
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
     * Manage Sidebar HTML
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_sidebar_html($request) {
        $nonce = $request->get_param('nonce');
        if ( ! wp_verify_nonce( $nonce, 'woolentor_verifynonce' ) ) {
            return new \WP_Error('rest_forbidden', __('Sorry, you are not allowed to activate plugins.'), ['status' => 403]);
        }
        ob_start();
        woolentor_opt_load_template('sidebar');
        $sidebar_content = ob_get_clean();
        
        return rest_ensure_response([
            'content' => $sidebar_content
        ]);
    }

    /**
     * Free VS Pro HTML
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_free_vs_pro_html($request) {
        $nonce = $request->get_param('nonce');
        if ( ! wp_verify_nonce( $nonce, 'woolentor_verifynonce' ) ) {
            return new \WP_Error('rest_forbidden', __('Sorry, you are not allowed to activate plugins.'), ['status' => 403]);
        }
        ob_start();
        woolentor_opt_load_template('freevspro');
        $freevspro_content = ob_get_clean();
        
        return rest_ensure_response([
            'content' => $freevspro_content
        ]);
    }

}