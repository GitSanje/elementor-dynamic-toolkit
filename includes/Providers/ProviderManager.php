<?php
/**
 * Dynamic data provider registry.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

final class ProviderManager {

	/**
	 * @var array<string, DataProviderInterface>
	 */
	private array $providers = [];

	public function __construct() {
		$this->register( new CoreProvider() );

		if ( function_exists( 'get_field' ) ) {
			$this->register( new ACFProvider() );
		}
	}

	public function register( DataProviderInterface $provider ): self {
		$this->providers[ $provider->get_id() ] = $provider;
		return $this;
	}

	public function get_providers(): array {
		$providers = $this->providers;
		$providers = apply_filters( 'edt/data_providers', $providers );

		return array_values( $providers );
	}

	public function get( string $provider_id ): ?DataProviderInterface {
		$providers = $this->get_providers();

		foreach ( $providers as $provider ) {
			if ( $provider instanceof DataProviderInterface && $provider_id === $provider->get_id() ) {
				return $provider;
			}
		}

		return null;
	}
}
