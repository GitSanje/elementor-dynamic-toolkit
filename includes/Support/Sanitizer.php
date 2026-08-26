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
		$value = strtoupper( (string) $value );
		return in_array( $value, [ 'ASC', 'DESC' ], true ) ? $value : 'DESC';
	}

	public static function sanitize_order_by( mixed $value ): string {
		$allowed = [ 'date', 'title', 'menu_order', 'modified', 'rand', 'ID' ];
		$value   = sanitize_key( (string) $value );
		return in_array( $value, $allowed, true ) ? $value : 'date';
	}

	public static function sanitize_post_type( mixed $value ): string {
		$post_type = sanitize_key( (string) $value );
		return post_type_exists( $post_type ) ? $post_type : 'post';
	}
}
