<?php
/**
 * Shared condition visibility control settings.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class VisibilityControl {

	public static function add_controls( \Elementor\Widget_Base $widget ): void {
		$widget->start_controls_section(
			'edt_visibility_section',
			[
				'label' => esc_html__( 'Dynamic Visibility & Conditions', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$widget->add_control(
			'edt_visibility_enabled',
			[
				'label'        => esc_html__( 'Enable Visibility Rules', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'elementor-dynamic-toolkit' ),
				'label_off'    => esc_html__( 'No', 'elementor-dynamic-toolkit' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$widget->add_control(
			'edt_visibility_action',
			[
				'label'     => esc_html__( 'Action', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'show' => esc_html__( 'Show Element If Conditions Met', 'elementor-dynamic-toolkit' ),
					'hide' => esc_html__( 'Hide Element If Conditions Met', 'elementor-dynamic-toolkit' ),
				],
				'default'   => 'show',
				'condition' => [ 'edt_visibility_enabled' => 'yes' ],
			]
		);

		$widget->add_control(
			'edt_visibility_login',
			[
				'label'     => esc_html__( 'User Status', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => esc_html__( 'Any (No restriction)', 'elementor-dynamic-toolkit' ),
					'logged' => esc_html__( 'Logged-in users only', 'elementor-dynamic-toolkit' ),
					'guest'  => esc_html__( 'Guests (Logged-out) only', 'elementor-dynamic-toolkit' ),
				],
				'default'   => '',
				'condition' => [ 'edt_visibility_enabled' => 'yes' ],
			]
		);

		$widget->add_control(
			'edt_visibility_role',
			[
				'label'       => esc_html__( 'Restrict to User Roles', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => self::get_role_options(),
				'condition'   => [
					'edt_visibility_enabled' => 'yes',
					'edt_visibility_login'   => 'logged',
				],
				'label_block' => true,
			]
		);

		$widget->add_control(
			'edt_visibility_device',
			[
				'label'     => esc_html__( 'Device Viewport', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''        => esc_html__( 'All Devices', 'elementor-dynamic-toolkit' ),
					'desktop' => esc_html__( 'Desktop Only', 'elementor-dynamic-toolkit' ),
					'mobile'  => esc_html__( 'Mobile Only', 'elementor-dynamic-toolkit' ),
				],
				'default'   => '',
				'condition' => [ 'edt_visibility_enabled' => 'yes' ],
			]
		);

		$widget->end_controls_section();
	}

	public static function get_role_options(): array {
		if ( ! function_exists( 'wp_roles' ) ) {
			return [
				'administrator' => 'Administrator',
				'editor'        => 'Editor',
				'author'        => 'Author',
				'subscriber'    => 'Subscriber',
			];
		}

		$roles = wp_roles()->roles;
		$options = [];
		foreach ( $roles as $slug => $data ) {
			$options[ $slug ] = $data['name'];
		}

		return $options;
	}
}
