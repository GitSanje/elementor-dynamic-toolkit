<?php
/**
 * Settings management.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Admin;

defined( 'ABSPATH' ) || exit;

final class Settings {

	public const OPTION_NAME = 'edt_settings';

	public static function get_defaults(): array {
		return [
			'enable_cache'       => true,
			'cache_ttl'          => 300,
			'enable_debug'       => false,
			'asset_mode'         => 'smart', // 'smart' or 'all'
			'enable_async_ctrl'  => true,
			'enable_masonry'     => true,
			'default_post_limit' => 12,
		];
	}

	public static function get(): array {
		$settings = get_option( self::OPTION_NAME, [] );
		return wp_parse_args( is_array( $settings ) ? $settings : [], self::get_defaults() );
	}

	public static function get_bool( string $key, bool $default = false ): bool {
		$settings = self::get();
		return isset( $settings[ $key ] ) ? (bool) $settings[ $key ] : $default;
	}

	public static function get_int( string $key, int $default = 0 ): int {
		$settings = self::get();
		return isset( $settings[ $key ] ) ? (int) $settings[ $key ] : $default;
	}

	public static function get_string( string $key, string $default = '' ): string {
		$settings = self::get();
		return isset( $settings[ $key ] ) ? (string) $settings[ $key ] : $default;
	}
}
