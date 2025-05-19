<?php
namespace WoolentorOptions\Admin;

class Dashboard_Widget {

    /**
     * [init]
     */
    public function init() {
        add_action( 'wp_dashboard_setup', [ $this, 'dashboard_widget' ], 9999 );
    }

    /**
     * [dashboard_widget]
     */
    public function dashboard_widget() {
        wp_add_dashboard_widget( 
            'hasthemes-dashboard-stories', 
            esc_html__( 'HasThemes Stories', 'woolentor' ), 
            [ $this, 'dashboard_hasthemes_widget' ] 
        );

		// Metaboxes Array.
		global $wp_meta_boxes;

		$dashboard_widget_list = $wp_meta_boxes['dashboard']['normal']['core'];

        $hastheme_dashboard_widget = [
            'hasthemes-dashboard-stories' => $dashboard_widget_list['hasthemes-dashboard-stories']
        ];

        $all_dashboard_widget = array_merge( $hastheme_dashboard_widget, $dashboard_widget_list );

		$wp_meta_boxes['dashboard']['normal']['core'] = $all_dashboard_widget;
    }

    /**
     * [dashboard_hasthemes_widget] Dashboard Stories Widget
     * @return [void]
     */
    public function dashboard_hasthemes_widget() {
        ob_start();
        woolentor_opt_load_template('widget');
        echo ob_get_clean();
    }

}
