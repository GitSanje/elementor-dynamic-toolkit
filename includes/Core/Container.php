<?php
/**
 * Lightweight Service Container.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Core;

defined( 'ABSPATH' ) || exit;

final class Container {

	private static ?self $instance = null;

	/**
	 * @var array<string, mixed>
	 */
	private array $services = [];

	/**
	 * @var array<string, callable>
	 */
	private array $factories = [];

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function set( string $id, mixed $service ): void {
		$this->services[ $id ] = $service;
	}

	public function bind( string $id, callable $factory ): void {
		$this->factories[ $id ] = $factory;
	}

	public function get( string $id ): mixed {
		if ( isset( $this->services[ $id ] ) ) {
			return $this->services[ $id ];
		}

		if ( isset( $this->factories[ $id ] ) ) {
			$this->services[ $id ] = call_user_func( $this->factories[ $id ], $this );
			return $this->services[ $id ];
		}

		return null;
	}

	public function has( string $id ): bool {
		return isset( $this->services[ $id ] ) || isset( $this->factories[ $id ] );
	}

	public function clear(): void {
		$this->services  = [];
		$this->factories = [];
	}
}
