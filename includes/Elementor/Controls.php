<?php
/**
 * Elementor custom control registration boundary.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

use EDT\Controls\AsyncSelectControl;
use EDT\Controls\ConditionBuilderControl;

defined( 'ABSPATH' ) || exit;

final class Controls {

	public function register(): void {
		add_action(
			'elementor/controls/register',
			static function ( $controls_manager ): void {
				if ( class_exists( AsyncSelectControl::class ) && method_exists( $controls_manager, 'register' ) ) {
					$controls_manager->register( new AsyncSelectControl() );
				}

				if ( class_exists( ConditionBuilderControl::class ) && method_exists( $controls_manager, 'register' ) ) {
					$controls_manager->register( new ConditionBuilderControl() );
				}
			}
		);
	}
}