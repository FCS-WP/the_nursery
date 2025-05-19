<?php
namespace VamtamElementor\Widgets\Tabs;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'tabs' ) ) {
	return;
}

function add_disable_def_anim_control_content_tab( $controls_manager, $widget ) {
	$widget->add_control(
		'disable_def_anim',
		[
			'label' => __( 'Disable Default Tab Animation', 'vamtam-elementor-integration' ),
			'description' => __( 'Disables the default tab switching animation.', 'vamtam-elementor-integration' ),
			'type' => $controls_manager::SWITCHER,
			'prefix_class' => 'vamtam-has-',
			'return_value' => 'disable-def-anim',
		]
	);
}

// Content - Tabs section.
add_action( 'elementor/element/tabs/section_tabs/before_section_end', function( $widget, $args ) {
	$controls_manager = \Elementor\Plugin::instance()->controls_manager;
	add_disable_def_anim_control_content_tab( $controls_manager, $widget );
}, 10, 2 );

// Vamtam_Widget_Tabs.
function widgets_registered() {
	class Vamtam_Widget_Tabs extends \Elementor\Widget_Tabs {
		public $extra_depended_scripts = [
			'vamtam-tabs',
		];

		public function get_script_depends() {
			return [
				'vamtam-tabs',
			];
		}

		// Extend constructor.
		public function __construct($data = [], $args = null) {
			parent::__construct($data, $args);

			$this->register_assets();

			$this->add_extra_script_depends();
		}

		// Register the assets the widget depends on.
		public function register_assets() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_script(
				'vamtam-tabs',
				VAMTAM_ELEMENTOR_INT_URL . '/assets/js/widgets/tabs/vamtam-tabs' . $suffix . '.js',
				[
					'elementor-frontend'
				],
				\VamtamElementorIntregration::PLUGIN_VERSION,
				true
			);
		}

		// Assets the widget depends upon.
		public function add_extra_script_depends() {
			// Scripts
			foreach ( $this->extra_depended_scripts as $script ) {
				$this->add_script_depends( $script );
			}
		}
	}

	// Replace current tabs widget with our extended version.
	$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
	$widgets_manager->unregister( 'tabs' );
	$widgets_manager->register( new Vamtam_Widget_Tabs );
}
add_action( \Vamtam_Elementor_Utils::get_widgets_registration_hook(), __NAMESPACE__ . '\widgets_registered', 100 );

if ( vamtam_theme_supports( 'tabs--underline-animation' ) ) {
	function add_tabs_style_section_controls( $controls_manager, $widget ) {
		$widget->start_injection( [
			'of' => 'heading_content',
			'at' => 'before',
		] );
		// Use Underline Anim.
		$widget->add_control(
			'vamtam_underline_anim',
			[
				'label' => __( 'Use Underline Animation', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SWITCHER,
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'underline-anim',
			]
		);
		// Width
		$widget->add_control(
			'vamtam_underline_width',
			[
				'label' => __( 'Width', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 10,
						'min' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-tabs-wrapper' => '--vamtam-underline-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'vamtam_underline_anim!' => '',
				]
			]
		);
		// Underline Bg Color.
		$widget->add_control(
			'vamtam_underline_bg_color',
			[
				'label' => __( 'Underline Bg Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-tabs-wrapper' => '--vamtam-underline-bg-color: {{VALUE}};',
				],
				'condition' => [
					'vamtam_underline_anim!' => '',
				]
			]
		);
		// Underline Bg Color.
		$widget->add_control(
			'vamtam_active_underline_bg_color',
			[
				'label' => __( 'Active Underline Bg Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-tabs-wrapper' => '--vamtam-active-underline-bg-color: {{VALUE}};',
				],
				'condition' => [
					'vamtam_underline_anim!' => '',
				]
			]
		);
		$widget->end_injection();
	}
	// Style - Tabs section
	function section_tabs_style_before_section_end( $widget, $args ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		add_tabs_style_section_controls( $controls_manager, $widget );
	}
	add_action( 'elementor/element/tabs/section_tabs_style/before_section_end', __NAMESPACE__ . '\section_tabs_style_before_section_end', 10, 2 );

}
