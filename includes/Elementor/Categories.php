<?php
/**
 * Elementor widget category registration.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

defined( 'ABSPATH' ) || exit;

final class Categories {

	public const SLUG = 'elementor-dynamic-kit-addons';

	public function register(): void {
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
	}

	public function register_category( $elements_manager ): void {
		$elements_manager->add_category(
			self::SLUG,
			[
				'title' => esc_html__( 'Elementor Dynamic Kit Addons', 'elementor-dynamic-toolkit' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}
}