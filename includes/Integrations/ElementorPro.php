<?php
/**
 * Optional Elementor Pro compatibility shim.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Integrations;

defined( 'ABSPATH' ) || exit;

final class ElementorPro {

	public function register(): void {
		if ( ! class_exists( '\\ElementorPro\\Plugin' ) ) {
			return;
		}

		add_action( 'elementor/init', [ $this, 'bootstrap' ] );
	}

	public function bootstrap(): void {
		// Reserved for Elementor Pro-specific integrations when/if they become available.
	}
}
