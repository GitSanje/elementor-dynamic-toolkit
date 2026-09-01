<?php
/**
 * Elementor Assets Integration.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

use EDT\Services\AssetService;

defined( 'ABSPATH' ) || exit;

final class Assets {

	public function register(): void {
		$service = new AssetService();

		add_action( 'elementor/editor/after_enqueue_styles', [ $service, 'enqueue_editor_assets' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $service, 'enqueue_editor_assets' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $service, 'enqueue_frontend_assets' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $service, 'register_frontend_assets' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $service, 'enqueue_frontend_assets' ] );
	}
}
