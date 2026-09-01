<?php
/**
 * WooCommerce Products Query Provider.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers\Query;

use EDT\Providers\QueryProviderInterface;

defined( 'ABSPATH' ) || exit;

final class WooCommerceQueryProvider implements QueryProviderInterface {

	public function get_id(): string {
		return 'woocommerce';
	}

	public function get_label(): string {
		return esc_html__( 'WooCommerce Products Query', 'elementor-dynamic-toolkit' );
	}

	public function supports( array $args ): bool {
		return ( 'product' === ( $args['post_type'] ?? '' ) ) && function_exists( 'WC' );
	}

	public function get_query_args( array $args ): array {
		$args['post_type'] = 'product';

		// Handle visibility and stock status if configured
		if ( ! empty( $args['hide_out_of_stock'] ) && function_exists( 'wc_get_product_visibility_term_ids' ) ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			if ( ! empty( $product_visibility_term_ids['outofstock'] ) ) {
				$args['tax_query']   = $args['tax_query'] ?? [];
				$args['tax_query'][] = [
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => [ $product_visibility_term_ids['outofstock'] ],
					'operator' => 'NOT IN',
				];
			}
		}

		return $args;
	}
}
