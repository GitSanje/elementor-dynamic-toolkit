<?php
/**
 * Sanitization helpers.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Support;

defined( 'ABSPATH' ) || exit;

final class Sanitizer {

	public static function sanitize_order( mixed $value ): string {
		$value = strtoupper( trim( (string) $value ) );
		return in_array( $value, [ 'ASC', 'DESC' ], true ) ? $value : 'DESC';
	}

	public static function sanitize_order_by( mixed $value ): string {
		$allowed = [ 'date', 'title', 'menu_order', 'modified', 'rand', 'ID', 'comment_count', 'meta_value', 'meta_value_num' ];
		$value   = sanitize_key( (string) $value );
		return in_array( $value, $allowed, true ) ? $value : 'date';
	}

	public static function sanitize_post_type( mixed $value ): string {
		$post_type = sanitize_key( (string) $value );
		if ( function_exists( 'post_type_exists' ) && ! post_type_exists( $post_type ) ) {
			return 'post';
		}
		return $post_type ?: 'post';
	}

	public static function sanitize_key_array( mixed $values ): array {
		if ( ! is_array( $values ) ) {
			return [];
		}

		return array_values( array_filter( array_map( 'sanitize_key', $values ) ) );
	}

	public static function sanitize_int_array( mixed $values ): array {
		if ( ! is_array( $values ) ) {
			return [];
		}

		return array_values( array_filter( array_map( 'absint', $values ) ) );
	}
}
