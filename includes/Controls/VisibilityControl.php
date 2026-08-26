<?php
/**
 * Shared condition visibility control settings.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

defined( 'ABSPATH' ) || exit;

final class VisibilityControl {

	public static function add_controls( \Elementor\Widget_Base $widget ): void {
		$widget->start_controls_section(
			'edt_visibility_section',
			[
				'label' => esc_html__( 'Conditional Visibility', 'elementor-dynamic-toolkit' ),
			]
		);

		$widget->add_control(
			'edt_visibility_enabled',
			[
				'label'        => esc_html__( 'Enable visibility rule', 'elementor-dynamic-toolkit' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'elementor-dynamic-toolkit' ),
				'label_off'    => esc_html__( 'Hide', 'elementor-dynamic-toolkit' ),
				'return_value' => 'yes',
			]
		);

		$widget->add_control(
			'edt_visibility_login',
			[
				'label'     => esc_html__( 'User status', 'elementor-dynamic-toolkit' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					''       => esc_html__( 'Any user', 'elementor-dynamic-toolkit' ),
					'logged' => esc_html__( 'Logged-in users', 'elementor-dynamic-toolkit' ),
					'guest'  => esc_html__( 'Logged-out users', 'elementor-dynamic-toolkit' ),
				],
				'condition' => [ 'edt_visibility_enabled' => 'yes' ],
			]
		);

		$widget->end_controls_section();
	}

	public static function config( string $label = 'Show if condition' ): array {
		return [
			'label' => esc_html__( $label, 'elementor-dynamic-toolkit' ),
			'type'  => \Elementor\Controls_Manager::TEXTAREA,
		];
	}
}
