<?php
/**
 * WooCommerce Data Provider.
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
		return esc_html__( 'WooCommerce Store & Product Data', 'elementor-dynamic-toolkit' );
	}

	public function supports( string $field, int $object_id ): bool {
		return function_exists( 'WC' );
	}

	public function get_fields( int $object_id = 0 ): array {
		return [
			'price'          => esc_html__( 'Product Price', 'elementor-dynamic-toolkit' ),
			'regular_price'  => esc_html__( 'Regular Price', 'elementor-dynamic-toolkit' ),
			'sale_price'     => esc_html__( 'Sale Price', 'elementor-dynamic-toolkit' ),
			'sku'            => esc_html__( 'Product SKU', 'elementor-dynamic-toolkit' ),
			'stock_status'   => esc_html__( 'Stock Status', 'elementor-dynamic-toolkit' ),
			'stock_quantity' => esc_html__( 'Stock Quantity', 'elementor-dynamic-toolkit' ),
			'cart_count'     => esc_html__( 'Cart Item Count', 'elementor-dynamic-toolkit' ),
			'cart_total'     => esc_html__( 'Cart Total', 'elementor-dynamic-toolkit' ),
		];
	}

	public function get_value( string $field, int $object_id = 0, array $options = [] ): mixed {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return null;
		}

		if ( 'cart_count' === $field ) {
			return ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		}

		if ( 'cart_total' === $field ) {
			return ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_total() : '';
		}

		$product_id = $object_id > 0 ? $object_id : ( function_exists( 'get_the_ID' ) ? get_the_ID() : 0 );
		$product    = wc_get_product( $product_id );

		if ( ! $product ) {
			return null;
		}

		return match ( $field ) {
			'price'          => $product->get_price(),
			'regular_price'  => $product->get_regular_price(),
			'sale_price'     => $product->get_sale_price(),
			'sku'            => $product->get_sku(),
			'stock_status'   => $product->get_stock_status(),
			'stock_quantity' => $product->get_stock_quantity(),
			default          => null,
		};
	}

	public function format_value( mixed $value, array $options = [] ): string {
		if ( is_scalar( $value ) ) {
			return (string) $value;
		}

		return '';
	}
}