<?php
/**
 * Elementor integration bootstrap.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

use EDT\Constants;

defined( 'ABSPATH' ) || exit;

final class Elementor {

	private bool $registered = false;

	public function register(): void {
		if ( $this->registered ) {
			return;
		}

		if ( ! defined( 'ELEMENTOR_VERSION' ) || version_compare( ELEMENTOR_VERSION, Constants::MINIMUM_ELEMENTOR_VERSION, '<' ) ) {
			return;
		}

		( new Categories() )->register();
		( new Widgets() )->register();
		( new Controls() )->register();
		( new DynamicTags() )->register();
		( new Conditions() )->register();
		( new Assets() )->register();
		( new Editor() )->register();

		$this->registered = true;
	}
}