<?php
/**
 * Elementor custom control registration boundary.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

defined( 'ABSPATH' ) || exit;

final class Controls {

	public function register(): void {
		add_action(
			'elementor/controls/register',
			static function ( $controls_manager ): void {
				if ( class_exists( '\\EDT\\Controls\\QueryControl' ) ) {
					// The shared control library is exposed via helper classes and is used by widgets
					// instead of embedding repeated inline control definitions.
				}
			}
		);
	}
}