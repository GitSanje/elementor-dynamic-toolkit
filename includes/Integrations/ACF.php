<?php
/**
 * Optional ACF integration.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Integrations;

defined( 'ABSPATH' ) || exit;

final class ACF {

	public function register(): void {
		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		add_filter( 'edt/data_providers', [ $this, 'register_acf_provider' ] );
	}

	public function register_acf_provider( array $providers ): array {
		if ( ! isset( $providers['acf'] ) ) {
			$providers['acf'] = new \EDT\Providers\ACFProvider();
		}

		return $providers;
	}
}
