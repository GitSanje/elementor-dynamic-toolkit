<?php
/**
 * Registry for custom query providers.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

final class QueryProviderManager {

	/**
	 * @var array<string, QueryProviderInterface>
	 */
	private array $providers = [];

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
		foreach ( $this->get_all() as $provider ) {
			if ( $provider->supports( $args ) ) {
				return $provider;
			}
		}

		return null;
	}
}