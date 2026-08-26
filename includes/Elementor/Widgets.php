<?php
/**
 * Elementor widget registration.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

use EDT\Widgets\DynamicQuery\Widget;

defined( 'ABSPATH' ) || exit;

final class Widgets {

	public function register(): void {
		add_action(
			'elementor/widgets/register',
			static function ( $widgets_manager ): void {
				$widgets_manager->register( new Widget() );
			}
		);
	}
}