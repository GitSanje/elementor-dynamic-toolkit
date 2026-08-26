<?php
/**
 * Validation helpers.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Support;

defined( 'ABSPATH' ) || exit;

final class Validator {

	public static function is_valid_post_type( mixed $value ): bool {
		return is_string( $value ) && post_type_exists( sanitize_key( $value ) );
	}

	public static function is_valid_taxonomy( mixed $value, string $post_type = 'post' ): bool {
		if ( ! is_string( $value ) ) {
			return false;
		}

		$taxonomy = sanitize_key( $value );
		return taxonomy_exists( $taxonomy ) && is_object_in_taxonomy( $post_type, $taxonomy );
	}

	public static function is_numeric_positive_int( mixed $value ): bool {
		return is_numeric( $value ) && (int) $value > 0;
	}
}
