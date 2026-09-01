<?php
/**
 * Validator utility.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Support;

defined( 'ABSPATH' ) || exit;

final class Validator {

	public static function is_valid_post_type( string $post_type ): bool {
		$post_type = sanitize_key( $post_type );
		if ( ! function_exists( 'get_post_types' ) ) {
			return true;
		}
		$public = get_post_types( [ 'public' => true ] );
		return isset( $public[ $post_type ] );
	}

	public static function is_valid_taxonomy( string $taxonomy ): bool {
		$taxonomy = sanitize_key( $taxonomy );
		if ( ! function_exists( 'taxonomy_exists' ) ) {
			return true;
		}
		return taxonomy_exists( $taxonomy );
	}

	public static function is_valid_orderby( string $orderby ): bool {
		$allowed = [ 'date', 'title', 'menu_order', 'modified', 'rand', 'ID', 'comment_count', 'meta_value', 'meta_value_num' ];
		return in_array( sanitize_key( $orderby ), $allowed, true );
	}
}
