<?php
namespace VamtamElementor\Widgets\Button;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'button' ) ) {
	return;
}

if ( vamtam_theme_supports( 'button--fiore-button-type' ) ) {

	function add_button_section_controls( $controls_manager, $widget ) {
		$widget->start_injection( [
			'of' => 'text',
		] );
		$widget->add_control(
			'vamtam_force_wrap',
			[
				'label' => __( 'Force Text Wrap', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SWITCHER,
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'force-wrap',
				'condition' => [
					'button_type' => 'fiore',
				],
			]
		);
		$widget->add_responsive_control(
			'vamtam_vertical_text_space',
			[
				'label' => __( 'Vertical Text Space', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SLIDER,
				'desktop_default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'range' => [
					'px' => [
						'min' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selector_value' => 'line-height: {{SIZE}}{{UNIT}})',
				'selectors' => [
					'{{WRAPPER}} .elementor-button-text' => 'line-height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'button_type' => 'fiore',
					'vamtam_force_wrap!' => '',
				],
			]
		);
		$widget->add_responsive_control(
			'vamtam_text_position',
			[
				'label' => __( 'Text Position', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'allowed_dimensions' => [ 'top', 'left' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button-text' => 'top:{{TOP}}{{UNIT}};right:{{RIGHT}}{{UNIT}};bottom:{{BOTTOM}}{{UNIT}};left:{{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_type' => 'fiore',
				],
			]
		);
		$widget->end_injection();
	}

	function update_button_section_controls( $controls_manager, $widget ) {
		// Button Type.
		\Vamtam_Elementor_Utils::add_control_options( $controls_manager, $widget, 'button_type', [
			'options' => [
				'fiore' => __( 'Fiore', 'vamtam-elementor-integration' ),
			],
			'render_type' => 'template',
			]
		);
		// Size.
		\Vamtam_Elementor_Utils::add_control_options( $controls_manager, $widget, 'size', [
			'condition' => [
					'button_type!' => 'fiore',
				]
			]
		);
		// Align.
		\Vamtam_Elementor_Utils::replace_control_options( $controls_manager, $widget, 'align', [
			'prefix_class' => 'elementor%s-align-',
			'condition' => [
				'button_type!' => 'fiore',
			]
		]
		);
		$widget->start_injection( [
			'of' => 'align',
		] );
		$widget->add_responsive_control(
			'vamtam_align',
			[
				'label' => __( 'Alignment', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'vamtam-elementor-integration' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'vamtam-elementor-integration' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'vamtam-elementor-integration' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'vamtam-elementor-integration' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => 'center',
				'condition' => [
					'button_type' => 'fiore',
				]
			]
		);
		$widget->end_injection();
		// Icon Spacing.
		\Vamtam_Elementor_Utils::replace_control_options( $controls_manager, $widget, 'icon_indent', [
			'condition' => [
				'button_type!' => 'fiore',
			]
		]
		);
		$widget->start_injection( [
			'of' => 'icon_indent',
		] );
		$widget->add_responsive_control(
			'vamtam_icon_spacing',
			[
				'label' => __( 'Icon Spacing', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'allowed_dimensions' => [ 'top', 'left' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button-icon > *' => 'top:{{TOP}}{{UNIT}};right:{{RIGHT}}{{UNIT}};bottom:{{BOTTOM}}{{UNIT}};left:{{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_type' => 'fiore',
					'selected_icon[value]!' => '',
				],
			]
		);
		// Icon Stack.
		$widget->add_control(
			'vamtam_stack_icon',
			[
				'label' => __( 'Stack Icon', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SWITCHER,
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'icon-stacked',
				'condition' => [
					'button_type' => 'fiore',
					'selected_icon[value]!' => '',
				],
			]
		);
		$widget->end_injection();
	}

	function update_button_style_section_controls( $controls_manager, $widget ) {
		// Typography Line-Height.
		\Vamtam_Elementor_Utils::replace_control_options( $controls_manager, $widget, 'typography_line_height', [
			'condition' => [
				'button_type!' => 'fiore',
			]
		] );
		$widget->start_injection( [
			'of' => 'typography_line_height',
		] );
		$widget->add_responsive_control(
			'vamtam_typography_line_height',
			[
				'label' => _x( 'Line-Height', 'Typography Control', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SLIDER,
				'desktop_default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'range' => [
					'px' => [
						'min' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selector_value' => 'line-height: calc(16em + {{SIZE}}{{UNIT}})', // 16em is the default bg letter font-size.
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'line-height: calc(16em + {{SIZE}}{{UNIT}})',
				],
				'condition' => [
					'button_type' => 'fiore',
				],
			]
		);
		$widget->end_injection();
	}

	// Content - Button section
	function section_button_content_before_section_end( $widget, $args ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		update_button_section_controls( $controls_manager, $widget );
		add_button_section_controls( $controls_manager, $widget );
	}
	add_action( 'elementor/element/button/section_button/before_section_end', __NAMESPACE__ . '\section_button_content_before_section_end', 10, 2 );
}

if ( vamtam_theme_supports( [ 'button--underline-animation', 'button--fiore-button-type' ] ) ) {
	function add_button_style_section_controls( $controls_manager, $widget ) {
		if ( vamtam_theme_supports( 'button--underline-animation' ) ) {
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
				'underline_width',
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
						'{{WRAPPER}} .elementor-button-text::before, {{WRAPPER}} .elementor-button-text::after' => '--vamtam-underline-width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'vamtam_underline_anim!' => '',
					]
				]
			);
			// Underline Bg Color.
			$widget->add_control(
				'underline_bg_color',
				[
					'label' => __( 'Underline Bg Color', 'vamtam-elementor-integration' ),
					'type' => $controls_manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .elementor-button-text::before, {{WRAPPER}} .elementor-button-text::after' => '--vamtam-underline-bg-color: {{VALUE}};',
					],
					'condition' => [
						'vamtam_underline_anim!' => '',
					]
				]
			);
		}
		if ( vamtam_theme_supports( 'button--fiore-button-type' ) ) {
			$widget->start_injection( [
				'of' => 'background_color',
			] );
			// Bg Letter Color.
			$widget->add_control(
				'bg_letter_background_color',
				[
					'label' => __( 'Bg Letter Color', 'vamtam-elementor-integration' ),
					'type' => $controls_manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .vamtam-letter' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
					'condition' => [
						'button_type' => 'fiore',
					]
				]
			);
			$widget->end_injection();

			$widget->start_injection( [
				'of' => 'button_background_hover_color',
			] );
			// Bg Letter Color Hover.
			$widget->add_control(
				'bg_letter_background_color_hover',
				[
					'label' => __( 'Bg Letter Color', 'vamtam-elementor-integration' ),
					'type' => $controls_manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .vamtam-letter .inner' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
					'condition' => [
						'button_type' => 'fiore',
					]
				]
			);
			$widget->end_injection();
		}
	}
	// Style - Button section
	function section_style_before_section_end( $widget, $args ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		add_button_style_section_controls( $controls_manager, $widget );
		if ( vamtam_theme_supports( 'button--fiore-button-type' ) ) {
			update_button_style_section_controls( $controls_manager, $widget );
		}
	}
	add_action( 'elementor/element/button/section_style/before_section_end', __NAMESPACE__ . '\section_style_before_section_end', 10, 2 );

	// Vamtam_Widget_Button.
	function widgets_registered() {
		class Vamtam_Widget_Button extends \Elementor\Widget_Button {
			public $extra_depended_scripts = [
				'vamtam-button',
			];

			// Extend constructor.
			public function __construct($data = [], $args = null) {
				parent::__construct($data, $args);

				$this->register_assets();

				$this->add_extra_script_depends();
			}

			// Register the assets the widget depends on.
			public function register_assets() {
				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				if ( vamtam_theme_supports( 'button--underline-animation' ) ) {
					wp_register_script(
						'vamtam-button',
						VAMTAM_ELEMENTOR_INT_URL . '/assets/js/widgets/button/vamtam-button' . $suffix . '.js',
						[
							'elementor-frontend'
						],
						\VamtamElementorIntregration::PLUGIN_VERSION,
						true
					);
				}
			}

			// Assets the widget depends upon.
			public function add_extra_script_depends() {
				// Scripts

				if ( ! vamtam_theme_supports( 'button--underline-animation' ) ) {
					unset( $this->extra_depended_scripts[ 'vamtam-button' ] );
				}

				foreach ( $this->extra_depended_scripts as $script ) {
					$this->add_script_depends( $script );
				}
			}

			protected function render() {
				if ( ! vamtam_theme_supports( 'button--fiore-button-type' ) ) {
					return parent::render();
				}

				$settings = $this->get_settings_for_display();

				$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

				if ( ! empty( $settings['selected_icon']['value'] ) ) {
					$this->add_render_attribute( 'wrapper', 'class', 'has-icon' );
				}

				if ( ! empty( $settings['link']['url'] ) ) {
					$this->add_link_attributes( 'button', $settings['link'] );
					$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
				}

				$this->add_render_attribute( 'button', 'class', 'elementor-button' );
				$this->add_render_attribute( 'button', 'role', 'button' );

				if ( ! empty( $settings['button_css_id'] ) ) {
					$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
				}

				if ( ! empty( $settings['size'] ) ) {
					$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
				}

				if ( $settings['hover_animation'] ) {
					$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
				}

				?>
				<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
					<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<?php if ( $settings['button_type'] === 'fiore' && ! empty( $settings['text'] ) ) : ?>
							<div class="vamtam-content-wrap">
								<span class="vamtam-text-first-letter">
									<span class="vamtam-letter">
										<span class="outer">
											<span class="inner">
												<?php echo esc_html( $settings['text'][0] ); ?>
											</span>
										</span>
										<?php echo esc_html( $settings['text'][0] ); ?>
									</span>
								</span>
								<?php $this->render_text(); ?>
							</div>
						<?php else : ?>
							<?php $this->render_text(); ?>
						<?php endif; ?>
					</a>
				</div>
				<?php
			}
			// Override.
			protected function content_template() {
				if ( ! vamtam_theme_supports( 'button--fiore-button-type' ) ) {
					return parent::content_template();
				}
				?>
				<#
				view.addRenderAttribute( 'wrapper', 'class', 'elementor-button-wrapper' );

				if ( settings.selected_icon.value ) {
					view.addRenderAttribute( 'wrapper', 'class', 'has-icon' );
				}

				view.addRenderAttribute( 'text', 'class', 'elementor-button-text' );
				view.addInlineEditingAttributes( 'text', 'none' );
				var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
					migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
				#>
				<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
					<a id="{{ settings.button_css_id }}" class="elementor-button elementor-size-{{ settings.size }} elementor-animation-{{ settings.hover_animation }}" href="{{ settings.link.url }}" role="button">
							<# if ( settings.button_type === 'fiore' && settings.text ) { #>
								<div class="vamtam-content-wrap">
									<span class="vamtam-text-first-letter">
										<span class="vamtam-letter">
											<span class="outer">
												<span class="inner">{{{ settings.text[0] }}}</span>
											</span>
											{{{ settings.text[0] }}}
										</span>
									</span>
							<# } #>
							<span class="elementor-button-content-wrapper">
								<# if ( settings.icon || settings.selected_icon ) { #>
								<span class="elementor-button-icon elementor-align-icon-{{ settings.icon_align }}">
									<# if ( ( migrated || ! settings.icon ) && iconHTML.rendered ) { #>
										{{{ iconHTML.value }}}
									<# } else { #>
										<i class="{{ settings.icon }}" aria-hidden="true"></i>
									<# } #>
								</span>
								<# } #>
								<span {{{ view.getRenderAttributeString( 'text' ) }}}>{{{ settings.text }}}</span>
							</span>
							<# if ( settings.button_type === 'fiore' && settings.text ) { #>
								</div>
							<# } #>
					</a>
				</div>
				<?php
			}
		}

		// Replace current divider widget with our extended version.
		$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
		$widgets_manager->unregister( 'button' );
		$widgets_manager->register( new Vamtam_Widget_Button );
	}
	add_action( \Vamtam_Elementor_Utils::get_widgets_registration_hook(), __NAMESPACE__ . '\widgets_registered', 100 );
}
