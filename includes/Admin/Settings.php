<?php
/**
 * Settings management.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Admin;

defined( 'ABSPATH' ) || exit;

final class Settings {

	public static function get_defaults(): array {
		return [
			'enable_cache' => true,
			'enable_debug' => false,
		];
	}

	public static function get(): array {
		$settings = get_option( 'edt_settings', [] );
		return wp_parse_args( $settings, self::get_defaults() );
	}

	public static function get_bool( string $key, bool $default = false ): bool {
		$settings = self::get();
		return isset( $settings[ $key ] ) ? (bool) $settings[ $key ] : $default;
	}
}
