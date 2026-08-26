<?php
/**
 * Asset registration helpers.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Services;

use EDT\Constants;

defined( 'ABSPATH' ) || exit;

final class AssetService {

	public function register_editor_assets(): void {
		if ( ! is_admin() ) {
			return;
		}

		wp_register_style(
			'edt-editor',
			Constants::URL . 'assets/css/editor.css',
			[],
			Constants::VERSION
		);

		wp_register_script(
			'edt-editor',
			Constants::URL . 'assets/js/editor.js',
			[ 'jquery' ],
			Constants::VERSION,
			true
		);
	}

	public function register_frontend_assets(): void {
		wp_register_style(
			'edt-frontend',
			Constants::URL . 'assets/css/frontend.css',
			[],
			Constants::VERSION
		);

		wp_register_script(
			'edt-frontend',
			Constants::URL . 'assets/js/frontend.js',
			[ 'jquery' ],
			Constants::VERSION,
			true
		);
	}

	public function enqueue_editor_assets(): void {
		if ( ! is_admin() ) {
			return;
		}

		if ( file_exists( Constants::DIR . 'assets/css/editor.css' ) ) {
			wp_enqueue_style( 'edt-editor' );
		}

		if ( file_exists( Constants::DIR . 'assets/js/editor.js' ) ) {
			wp_enqueue_script( 'edt-editor' );
		}
	}

	public function enqueue_frontend_assets(): void {
		if ( file_exists( Constants::DIR . 'assets/css/frontend.css' ) ) {
			wp_enqueue_style( 'edt-frontend' );
		}

		if ( file_exists( Constants::DIR . 'assets/js/frontend.js' ) ) {
			wp_enqueue_script( 'edt-frontend' );
		}
	}
}
