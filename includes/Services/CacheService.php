<?php
/**
 * WordPress object-cache abstraction for the toolkit.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Services;

use EDT\Admin\Settings;
use EDT\Constants;

defined( 'ABSPATH' ) || exit;

final class CacheService {

	private const DEFAULT_GROUP = Constants::CACHE_GROUP;

	public function get( string $key, mixed $default = null, string $group = self::DEFAULT_GROUP ): mixed {
		if ( ! Settings::get_bool( 'enable_cache', true ) ) {
			return $default;
		}

		if ( ! function_exists( 'wp_cache_get' ) ) {
			return $default;
		}

		$value = wp_cache_get( $key, $group );
		return false === $value ? $default : $value;
	}

	public function set( string $key, mixed $value, int $ttl = 300, string $group = self::DEFAULT_GROUP ): bool {
		if ( ! Settings::get_bool( 'enable_cache', true ) ) {
			return false;
		}

		if ( ! function_exists( 'wp_cache_set' ) ) {
			return false;
		}

		return (bool) wp_cache_set( $key, $value, $group, $ttl );
	}

	public function delete( string $key, string $group = self::DEFAULT_GROUP ): bool {
		if ( ! function_exists( 'wp_cache_delete' ) ) {
			return false;
		}

		return (bool) wp_cache_delete( $key, $group );
	}

	public function flush_group( string $group = self::DEFAULT_GROUP ): bool {
		if ( function_exists( 'wp_cache_flush_group' ) ) {
			return (bool) wp_cache_flush_group( $group );
		}

		return false;
	}
}
