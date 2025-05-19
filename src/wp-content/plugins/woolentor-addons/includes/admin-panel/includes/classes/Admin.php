<?php
namespace WoolentorOptions;

/**
 * Admin class
 */
class Admin {

    /**
     * Initialize the class
     */
    public function __construct() {
        $this->remove_all_notices();
        $this->includes();
        $this->init();
    }

     /**
     * Include the controller classes
     *
     * @return void
     */
    private function includes() {
        if ( !class_exists( __NAMESPACE__ . '\Admin\Menu'  ) ) {
            require_once __DIR__ . '/Admin/Menu.php';
        }
        if ( !class_exists( __NAMESPACE__ . '\Admin\Options_Field'  ) ) {
            require_once __DIR__ . '/Admin/Options_field.php';
        }
        if ( !class_exists( __NAMESPACE__ . '\Admin\Dashboard_Widget'  ) ) {
            require_once __DIR__ . '/Admin/Dashboard_Widget.php';
        }
        if ( !class_exists( __NAMESPACE__ . '\Admin\Diagnostic_Data'  ) ) {
            require_once __DIR__ . '/Admin/Diagnostic_Data.php';
        }
    }

    /**
     * Admin Initilize
     *
     * @return void
     */
    public function init() {
        (new Admin\Menu())->init();
        (new Admin\Dashboard_Widget())->init();
    }

    /**
     * [remove_all_notices] remove addmin notices
     * @return [void]
     */
    public function remove_all_notices(){
        add_action('in_admin_header', function (){
            $current_screen = get_current_screen();
            $hide_screen = ['shoplentor_page_woolentor','edit-woolentor-template','woolentor-template'];
            if(  in_array( $current_screen->id, $hide_screen) ){
                remove_all_actions('admin_notices');
                remove_all_actions('all_admin_notices');
            }
        }, 1000);
    }

}