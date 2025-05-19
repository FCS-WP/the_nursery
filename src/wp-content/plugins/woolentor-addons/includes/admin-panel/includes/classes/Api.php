<?php
namespace WoolentorOptions;

use WP_REST_Controller;

/**
 * REST_API Handler
 */
class Api extends WP_REST_Controller {

    /**
     * [__construct description]
     */
    public function __construct() {
        $this->includes();

        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    /**
     * Include the controller classes
     *
     * @return void
     */
    private function includes() {
        if ( !class_exists( __NAMESPACE__ . '\Api\Settings'  ) ) {
            require_once __DIR__ . '/Api/Settings.php';
        }
        if (!class_exists(__NAMESPACE__ . '\Api\Plugins')) {
            require_once __DIR__ . '/Api/Plugins.php';
        }
        if ( !class_exists( __NAMESPACE__ . '\Api\Static_Content'  ) ) {
            require_once __DIR__ . '/Api/Static_Content.php';
        }
        if ( !class_exists( __NAMESPACE__ . '\Api\Custom_Actions'  ) ) {
            require_once __DIR__ . '/Api/Custom_Actions.php';
        }
        if ( !class_exists( __NAMESPACE__ . '\Api\ChangeLog'  ) ) {
            require_once __DIR__ . '/Api/ChangeLog.php';
        }
    }

    /**
     * Register the API routes
     *
     * @return void
     */
    public function register_routes() {
        (new Api\Settings())->register_routes();
        (new Api\Plugins())->register_routes();
        (new Api\Static_Content())->register_routes();
        (new Api\Custom_Actions())->register_routes();
        (new Api\ChangeLog())->register_routes();
    }

}