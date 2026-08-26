<?php
/**
 * Optional WooCommerce integration.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Integrations;

defined( 'ABSPATH' ) || exit;

final class WooCommerce {

	public function register(): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_filter( 'edt/data_providers', [ $this, 'register_wc_provider' ] );
	}

	public function register_wc_provider( array $providers ): array {
		$providers['woocommerce'] = new \EDT\Providers\WooCommerceProvider();

		return $providers;
	}
}
