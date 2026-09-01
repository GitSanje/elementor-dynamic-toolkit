<?php
/**
 * Shared helper utilities.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Support;

use EDT\Constants;

defined( 'ABSPATH' ) || exit;

final class Helpers {

	public static function get_public_post_types(): array {
		if ( ! function_exists( 'get_post_types' ) ) {
			return [ 'post' => 'Posts', 'page' => 'Pages' ];
		}
		$types = get_post_types( [ 'public' => true ], 'objects' );
		return is_array( $types ) ? $types : [];
	}

	public static function get_post_type_options(): array {
		$options = [];
		foreach ( self::get_public_post_types() as $name => $post_type ) {
			$label = is_object( $post_type ) ? ( $post_type->labels->singular_name ?? $post_type->labels->name ?? $name ) : (string) $post_type;
			$options[ $name ] = $label;
		}

		return $options;
	}

	public static function get_public_taxonomies( string $post_type = 'post' ): array {
		if ( ! function_exists( 'get_object_taxonomies' ) ) {
			return [];
		}
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		return is_array( $taxonomies ) ? $taxonomies : [];
	}

	public static function get_taxonomy_options( string $post_type = 'post' ): array {
		$options = [];
		foreach ( self::get_public_taxonomies( $post_type ) as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->labels->singular_name ?? $taxonomy->labels->name ?? $taxonomy->name;
		}

		return $options;
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
		if ( function_exists( 'get_the_ID' ) ) {
			$id = get_the_ID();
			if ( $id ) {
				return absint( $id );
			}
		}

		if ( function_exists( 'get_queried_object_id' ) ) {
			return absint( get_queried_object_id() );
		}

		return 0;
	}

	public static function maybe_get_editor_context(): array {
		return [
			'post_id' => self::get_current_post_id(),
			'device'  => function_exists( 'wp_is_mobile' ) && wp_is_mobile() ? 'mobile' : 'desktop',
			'user_id' => function_exists( 'get_current_user_id' ) ? get_current_user_id() : 0,
		];
	}
}
