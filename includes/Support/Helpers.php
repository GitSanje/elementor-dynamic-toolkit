<?php
/**
 * Shared helper utilities for the toolkit.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Support;

defined( 'ABSPATH' ) || exit;

final class Helpers {

	public static function get_public_post_types(): array {
		$types = get_post_types( [ 'public' => true ], 'objects' );
		return is_array( $types ) ? $types : [];
	}

	public static function get_public_taxonomies( string $post_type = 'post' ): array {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		return is_array( $taxonomies ) ? $taxonomies : [];
	}

	public static function normalize_bool( mixed $value ): bool {
		if ( is_bool( $value ) ) {
			return $value;
		}

		if ( is_string( $value ) ) {
			return in_array( strtolower( trim( $value ) ), [ '1', 'true', 'yes', 'on' ], true );
		}

		return (bool) $value;
	}

	public static function get_current_post_id(): int {
		return absint( get_the_ID() );
	}

	public static function maybe_get_editor_context(): array {
		return [
			'post_id' => self::get_current_post_id(),
			'device'  => wp_is_mobile() ? 'mobile' : 'desktop',
		];
	}
}
