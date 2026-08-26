<?php
/**
 * WordPress cache abstraction for the toolkit.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Services;

defined( 'ABSPATH' ) || exit;

final class CacheService {

	private const DEFAULT_GROUP = 'edt';

	public function get( string $key, mixed $default = null, string $group = self::DEFAULT_GROUP ): mixed {
		$value = wp_cache_get( $key, $group );
		return false === $value ? $default : $value;
	}

	public function set( string $key, mixed $value, int $ttl = 300, string $group = self::DEFAULT_GROUP ): bool {
		return wp_cache_set( $key, $value, $group, $ttl );
	}

	public function delete( string $key, string $group = self::DEFAULT_GROUP ): bool {
		return wp_cache_delete( $key, $group );
	}
}
