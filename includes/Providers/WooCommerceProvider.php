<?php
/**
 * WooCommerce product data provider.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

final class WooCommerceProvider implements DataProviderInterface {

	public function get_id(): string {
		return 'woocommerce';
	}

	public function get_label(): string {
		return esc_html__( 'WooCommerce', 'elementor-dynamic-toolkit' );
	}

	public function supports( string $field, int $object_id ): bool {
		return function_exists( 'wc_get_product' ) && '' !== $field && false !== wc_get_product( $object_id );
	}

	public function get_fields( int $object_id ): array {
		return [ 'price', 'regular_price', 'sale_price', 'sku', 'stock_status', 'stock_quantity', 'rating' ];
	}

	public function get_value( string $field, int $object_id ) {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return null;
		}

		$product = wc_get_product( $object_id );
		if ( ! $product ) {
			return null;
		}

		$values = [
			'price'          => $product->get_price(),
			'regular_price'  => $product->get_regular_price(),
			'sale_price'     => $product->get_sale_price(),
			'sku'            => $product->get_sku(),
			'stock_status'   => $product->get_stock_status(),
			'stock_quantity' => $product->get_stock_quantity(),
			'rating'         => $product->get_average_rating(),
		];

		return $values[ sanitize_key( $field ) ] ?? null;
	}
}