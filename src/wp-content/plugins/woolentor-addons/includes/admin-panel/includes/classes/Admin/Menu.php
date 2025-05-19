<?php
namespace WoolentorOptions\Admin;

class Menu {

    public $menupage = [];

    /**
     * Parent Menu Page Slug
     */
    const MENU_PAGE_SLUG = 'woolentor_page';

    /**
     * Menu capability
     */
    const MENU_CAPABILITY = 'manage_options';

    /**
     * [init]
     */
    public function init() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ], 25 );

        // Extension Menu in Last Position
        add_action( 'admin_menu', [ $this, 'extension_admin_menu' ], 226 );

        // Upgrade Pro Menu
        if( !is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') ){
            add_action( 'admin_menu', [$this, 'add_upgrade_pro_menu'], 228 );
            add_action('admin_head', [ $this, 'admin_menu_item_adjust'] );
            add_action('admin_head', [ $this, 'enqueue_admin_head_scripts'], 11 );
        }
    }

    /**
     * Register Menu
     *
     * @return void
     */
    public function admin_menu(){
        global $submenu;

        $parent_slug = 'woolentor_page';
        $sub_setting_slug = 'woolentor';
        $capability  = 'manage_options';

        $parent_hook = add_menu_page(
            esc_html__( 'ShopLentor', 'woolentor' ),
            esc_html__( 'ShopLentor', 'woolentor' ), 
            self::MENU_CAPABILITY,
            self::MENU_PAGE_SLUG,
            '',
            WOOLENTOROPT_ASSETS.'/images/icons/menu-bar_20x20.png',
            '58.7'
        );

        $sub_hook = add_submenu_page(
            self::MENU_PAGE_SLUG,
            esc_html__( 'Settings', 'woolentor' ),
            esc_html__( 'Settings', 'woolentor' ),
            self::MENU_CAPABILITY,
            $sub_setting_slug,
            [ $this, 'plugin_page' ]
        );

        // Remove Parent Submenu
        remove_submenu_page( 'woolentor_page','woolentor_page' );


        add_action( 'load-' . $sub_hook, [ $this, 'init_hooks'] );

    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Dequeue Scripts from our plugin admin screen
        add_action( 'admin_head', [ $this, 'dequeue_scripts_admin_head' ] );
    }

    /**
     * Load scripts and styles for the app
     *
     * @return void
     */
    public function enqueue_scripts() {

        // Manage Dequeue Scripts
        $this->dequeue_scripts();

        wp_enqueue_media();
        wp_enqueue_script( 'jquery' );
        wp_enqueue_style( 'simple-line-icons-wl' );
        wp_enqueue_style( 'woolentoropt-main' );
        wp_enqueue_style( 'woolentoropt-admin' );
        
        wp_enqueue_script( 'woolentoropt-admin' );

         // Add the type="module" attribute
         add_filter('script_loader_tag', function($tag, $handle) {
            if ('woolentoropt-admin' === $handle) {
                return str_replace('src', 'type="module" src', $tag);
            }
            return $tag;
        }, 10, 2);

        $option_field = Options_Field::instance();
        if( ! $option_field ){
            return;
        }

        // Get current user data
        $current_user = wp_get_current_user();
        $user_firstname = $current_user->user_firstname;

        $option_localize_script = [
            'adminUrl'      => admin_url( '/' ),
            'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
            'rootApiUrl'    => esc_url_raw( rest_url() ),
            'restNonce'     => wp_create_nonce( 'wp_rest' ),
            'verifynonce'   => wp_create_nonce( 'woolentor_verifynonce' ),
            'postUrl'       => admin_url( 'admin-post.php' ),
            'ajaxNonce'     => wp_create_nonce( 'ajax-nonce' ),
            'licenseNonce'  => wp_create_nonce( 'woolentor_license_r' ),
            'licenseEmail'  => get_option( 'WooLentorPro_lic_email', get_bloginfo( 'admin_email' ) ),
            'assetsUrl'     => WOOLENTOROPT_ASSETS,
            'isPro'         => woolentor_is_pro() ? 'yes' : 'no',
            'tabs'          => $option_field->get_settings_tabs(),
            'settings'      => $option_field->get_registered_settings(),
            'options'       => woolentor_opt_get_options( $option_field->get_registered_settings() ),
            'userData'      => [
                'name'      => $current_user->display_name,
                'firstName' => $current_user->user_firstname,
                'lastName'  => $current_user->user_lastname,
                'email'     => $current_user->user_email,
                'showName'  => !empty( $user_firstname ) ? ucfirst( $user_firstname ) : ucfirst( $current_user->display_name ),
            ],
            'labels'        => [
                'pro' => __( 'Pro', 'woolentor' ),
                'modal' => [
                    'title' => __( 'BUY PRO', 'woolentor' ),
                    'buynow' => __( 'Buy Now', 'woolentor' ),
                    'desc' => __( 'Our free version is great, but it doesn\'t have all our advanced features. The best way to unlock all of the features in our plugin is by purchasing the pro version.', 'woolentor' )
                ],
                'saveButton' => [
                    'text'   => __( 'Save Settings', 'woolentor' ),
                    'saving' => __( 'Saving...', 'woolentor' ),
                    'saved'  => __( 'Data Saved', 'woolentor' ),
                ],
                'enableAllButton' => [
                    'enable'   => __( 'Enable All', 'woolentor' ),
                    'disable'  => __( 'Disable All', 'woolentor' ),
                ],
                'resetButton' => [
                    'text'   => __( 'Reset All Settings', 'woolentor' ),
                    'reseting'  => __( 'Resetting...', 'woolentor' ),
                    'reseted'  => __( 'All Data Restored', 'woolentor' ),
                    'alert' => [
                        'one'=>[
                            'title' => __( 'Are you sure?', 'woolentor' ),
                            'text' => __( 'It will reset all the settings to default, and all the changes you made will be deleted.', 'woolentor' ),
                            'confirm' => __( 'Yes', 'woolentor' ),
                            'cancel' => __( 'No', 'woolentor' ),
                        ],
                        'two'=>[
                            'title' => __( 'Reset!', 'woolentor' ),
                            'text' => __( 'All settings has been reset successfully.', 'woolentor' ),
                            'confirm' => __( 'OK', 'woolentor' ),
                        ]
                    ],
                ]
            ]
        ];
        wp_localize_script( 'woolentoropt-admin', 'woolentorOptions', $option_localize_script );
    }

    /**
     * Dequeue Scripts from our plugin admin screen
     * @return void
     */
    public function dequeue_scripts_admin_head(){
        // Remove Woolentor JS from Our Dashboard Screen
        if ( wp_script_is( 'elementor-ai-media-library', 'enqueued' ) ) {
            wp_dequeue_script('elementor-ai-media-library');
            wp_deregister_script('elementor-ai-media-library');
        }
    }

    /**
     * Dequeue Scripts from our plugin admin screen
     * @return void
     */
    public function dequeue_scripts() {
        // Remove Elementor JS from Our Dashboard Screen
        if ( wp_script_is( 'elementor-common', 'enqueued' ) ) {
            wp_dequeue_script('elementor-common');
            wp_deregister_script('elementor-common');
        }
        if ( wp_script_is( 'elementor-import-export-admin', 'enqueued' ) ) {
            wp_dequeue_script('elementor-import-export-admin');
            wp_deregister_script('elementor-import-export-admin');
        }
        if ( wp_script_is( 'elementor-app-loader', 'enqueued' ) ) {
            wp_dequeue_script('elementor-app-loader');
            wp_deregister_script('elementor-app-loader');
        }
        if ( wp_script_is( 'elementor-admin', 'enqueued' ) ) {
            wp_dequeue_script('elementor-admin');
            wp_deregister_script('elementor-admin');
        }

        if ( wp_script_is( 'wc-enhanced-select', 'enqueued' ) ) {
            wp_dequeue_script('wc-enhanced-select');
            wp_deregister_script('wc-enhanced-select');
        }
        // UNISEND for WooCommerce Plugin script dequeue
        if ( wp_script_is( 'woo-lithuaniapost', 'enqueued' ) ) {
            wp_dequeue_script('woo-lithuaniapost');
            wp_deregister_script('woo-lithuaniapost');
        }
    }

    /**
     * Render our admin page
     *
     * @return void
     */
    public function plugin_page() {
        // if( !is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') ){
        //     $this->offer_notice();
        // }
        ob_start();
        woolentor_opt_load_template('main');
        echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Extention Menu
     * @return void
     */
    public function extension_admin_menu(){
        global $submenu;
        $sub_setting_slug = 'woolentor';

        if ( current_user_can( self::MENU_CAPABILITY ) ) {
            $submenu[ self::MENU_PAGE_SLUG ][] = array( esc_html__( 'Extension', 'woolentor' ), self::MENU_CAPABILITY, 'admin.php?page=' . $sub_setting_slug . '#/extension' );
        }

    }

    /**
     * [add_upgrade_pro_menu] Admin Menu
     */
    public function add_upgrade_pro_menu(){
        add_submenu_page(
            self::MENU_PAGE_SLUG, 
            esc_html__('Upgrade to Pro', 'woolentor'),
            esc_html__('Upgrade to Pro', 'woolentor'), 
            self::MENU_CAPABILITY,
            'https://woolentor.com/pricing/?utm_source=admin&utm_medium=mainmenu&utm_campaign=free'
        );
    }

    // Add Class For pro Menu Item
    public function admin_menu_item_adjust(){
        global $submenu;

		// Check WooLentor Menu page exist or not
		if ( ! isset( $submenu['woolentor_page'] ) ) {
			return;
		}

        $position = key(
			array_filter( $submenu['woolentor_page'],  
				static function( $item ) {
					return strpos( $item[2], 'https://woolentor.com/pricing/?utm_source=admin&utm_medium=mainmenu&utm_campaign=free' ) !== false;
				}
			)
		);

        if ( isset( $submenu['woolentor_page'][ $position ][4] ) ) {
			$submenu['woolentor_page'][ $position ][4] .= ' woolentor-upgrade-pro';
		} else {
			$submenu['woolentor_page'][ $position ][] = 'woolentor-upgrade-pro';
		}
    }

    // Add Custom scripts for pro menu item
    public function enqueue_admin_head_scripts(){
        $styles = '';
        $scripts = '';

        $styles .= '#adminmenu #toplevel_page_woolentor_page a.woolentor-upgrade-pro { font-weight: 600; background-color: #f56640; color: #ffffff; display: block; text-align: center;}';
        $scripts .= 'jQuery(document).ready( function($) {
			$("#adminmenu #toplevel_page_woolentor_page a.woolentor-upgrade-pro").attr("target","_blank");  
		});';
		
		printf( '<style>%s</style>', $styles ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( '<script>%s</script>', $scripts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Manage Promo Notice for Setting page
     *
     * @return void
     */
    public function offer_notice(){
        if( !isset( \WooLentor\Base::$template_info['notices'] ) || !is_array( \WooLentor\Base::$template_info['notices'] ) ){
            return;
        }

        $notice_info = \WooLentor\Base::$template_info['notices'][0];
        if( isset( $notice_info['status'] ) ){
            if( $notice_info['status'] == 0 ){
                return;
            }
        }else{
            return;
        }

        $message = $notice_info['description'] ? $notice_info['description'] : '';

        \WooLentor_Notices::add_notice(
			[
				'id'          => 'setting-page-promo-banner',
                'type'        => 'custom',
                'dismissible' => true,
				'message'     => $message,
                'close_by'    => 'transient'
			]
		);

    }

}
