<?php
/**
 * Admin bootstrap for the toolkit.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Admin;

defined( 'ABSPATH' ) || exit;

final class Admin {

	public function register(): void {
		add_action( 'admin_menu', [ $this, 'register_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	public function register_settings_page(): void {
		add_options_page(
			esc_html__( 'Elementor Dynamic Toolkit', 'elementor-dynamic-toolkit' ),
			esc_html__( 'EDT', 'elementor-dynamic-toolkit' ),
			'manage_options',
			'elementor-dynamic-toolkit',
			[ new SettingsPage(), 'render' ]
		);
	}

	public function register_settings(): void {
		register_setting( 'edt_settings', 'edt_settings' );
	}
}
