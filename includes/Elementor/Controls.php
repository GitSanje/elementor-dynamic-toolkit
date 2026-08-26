<?php
/**
 * Elementor custom control registration boundary.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

use EDT\Controls\QuerySelectControl;

defined( 'ABSPATH' ) || exit;

final class Controls {

	public function register(): void {
		add_action(
			'elementor/controls/register',
			static function ( $controls_manager ): void {
				if ( class_exists( QuerySelectControl::class ) ) {
					$controls_manager->register( new QuerySelectControl() );
				}
			}
		);
	}
}