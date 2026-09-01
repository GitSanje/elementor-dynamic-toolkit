<?php
/**
 * Registry for custom query providers.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

use EDT\Providers\Query\WPQueryProvider;
use EDT\Providers\Query\WooCommerceQueryProvider;

defined( 'ABSPATH' ) || exit;

final class QueryProviderManager {

	/**
	 * @var array<string, QueryProviderInterface>
	 */
	private array $providers = [];

	public function __construct() {
		$this->register( new WPQueryProvider() );
		$this->register( new WooCommerceQueryProvider() );
	}

	public function register( QueryProviderInterface $provider ): self {
		$this->providers[ sanitize_key( $provider->get_id() ) ] = $provider;
		return $this;
	}

	/**
	 * @return array<string, QueryProviderInterface>
	 */
	public function get_all(): array {
		$providers = apply_filters( 'edt/query_providers', $this->providers );

		return array_filter(
			is_array( $providers ) ? $providers : [],
			static fn ( mixed $provider ): bool => $provider instanceof QueryProviderInterface
		);
	}

	public function resolve( array $args ): ?QueryProviderInterface {
		$providers = $this->get_all();

		// Check specialized providers first (skip default fallback)
		foreach ( $providers as $id => $provider ) {
			if ( 'wp_query' !== $id && $provider->supports( $args ) ) {
				return $provider;
			}
		}

		return $providers['wp_query'] ?? null;
	}
}