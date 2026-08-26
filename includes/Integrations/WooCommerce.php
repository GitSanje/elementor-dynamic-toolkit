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
		$providers['woocommerce'] = new class implements \EDT\Providers\DataProviderInterface {
			public function get_id(): string { return 'woocommerce'; }
			public function get_label(): string { return esc_html__( 'WooCommerce', 'elementor-dynamic-toolkit' ); }
			public function supports( string $field, int $object_id ): bool { return false; }
			public function get_fields( int $object_id ): array { return []; }
			public function get_value( string $field, int $object_id ) { return null; }
		};

		return $providers;
	}
}
