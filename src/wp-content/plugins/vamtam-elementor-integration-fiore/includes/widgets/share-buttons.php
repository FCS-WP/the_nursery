<?php
namespace VamtamElementor\Widgets\ShareButtons;

// Extending the Share Buttons widget.

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'share-buttons' ) ) {
	return;
}

function update_custom_color_controls( $controls_manager, $widget ) {
	// Secondary Color.
	\Vamtam_Elementor_Utils::replace_control_options( $controls_manager, $widget, 'secondary_color', [
		'condition' => [
			/* Here, we essentially enable the secondary color option for the 'framed' skin (circle) as well.
				That's because the initial condition from elementor for this option is:
					'condition' => [
						'color_source' => 'custom', // Implicitly by the tabs.
						'skin!' => 'framed', // Explicitly when defining the option. (That's what we disable here.)
					],
				*/
			'color_source' => 'custom',
		],
	] );
}

// Styles - Background section.
function section_buttons_style_before_section_end( $widget, $args ) {
	$controls_manager = \Elementor\Plugin::instance()->controls_manager;
	update_custom_color_controls( $controls_manager, $widget );
}
add_action( 'elementor/element/share-buttons/section_buttons_style/before_section_end', __NAMESPACE__ . '\section_buttons_style_before_section_end', 10, 2 );
