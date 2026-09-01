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
		$this->register( new UserProvider() );
		$this->register( new OptionProvider() );

		if ( function_exists( 'get_field' ) ) {
			$this->register( new ACFProvider() );
		}

		if ( function_exists( 'WC' ) ) {
			$this->register( new WooCommerceProvider() );
		}
	}

	public function register( DataProviderInterface $provider ): self {
		$this->providers[ sanitize_key( $provider->get_id() ) ] = $provider;
		return $this;
	}

	/**
	 * @return array<string, DataProviderInterface>
	 */
	public function get_providers(): array {
		$providers = apply_filters( 'edt/data_providers', $this->providers );

		return is_array( $providers ) ? $providers : [];
	}

	public function get( string $provider_id ): ?DataProviderInterface {
		$providers = $this->get_providers();
		return $providers[ sanitize_key( $provider_id ) ] ?? null;
	}
}
