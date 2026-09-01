<?php
/**
 * WooCommerce Store & Cart Condition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class WooCondition implements ConditionInterface {

	public function get_id(): string {
		return 'woocommerce';
	}

	public function get_title(): string {
		return esc_html__( 'WooCommerce Cart & Product', 'elementor-dynamic-toolkit' );
	}

	public function get_group(): string {
		return esc_html__( 'WooCommerce', 'elementor-dynamic-toolkit' );
	}

	public function get_operators(): array {
		return [
			'cart_empty'     => esc_html__( 'Cart Is Empty', 'elementor-dynamic-toolkit' ),
			'cart_not_empty' => esc_html__( 'Cart Has Items', 'elementor-dynamic-toolkit' ),
			'in_stock'       => esc_html__( 'Product Is In Stock', 'elementor-dynamic-toolkit' ),
		];
	}

	public function evaluate( array $rule = [], array $context = [] ): bool {
		if ( ! function_exists( 'WC' ) ) {
			return false;
		}

		$operator = $rule['operator'] ?? 'cart_not_empty';

		if ( 'cart_empty' === $operator ) {
			return ! WC()->cart || WC()->cart->is_empty();
		}

		if ( 'cart_not_empty' === $operator ) {
			return WC()->cart && ! WC()->cart->is_empty();
		}

		if ( 'in_stock' === $operator && function_exists( 'wc_get_product' ) ) {
			$post_id = $context['post_id'] ?? get_the_ID();
			$product = wc_get_product( $post_id );
			return $product && $product->is_in_stock();
		}

		return true;
	}
}
