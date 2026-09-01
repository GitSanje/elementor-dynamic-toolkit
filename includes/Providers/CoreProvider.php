<?php
/**
 * Core WordPress Data Provider.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

final class CoreProvider implements DataProviderInterface {

	public function get_id(): string {
		return 'core';
	}

	public function get_label(): string {
		return esc_html__( 'WordPress Core & Post Meta', 'elementor-dynamic-toolkit' );
	}

	public function supports( string $field, int $object_id ): bool {
		return ! empty( $field );
	}

	public function get_fields( int $object_id = 0 ): array {
		if ( $object_id <= 0 ) {
			return [
				'post_title'   => esc_html__( 'Post Title', 'elementor-dynamic-toolkit' ),
				'post_excerpt' => esc_html__( 'Post Excerpt', 'elementor-dynamic-toolkit' ),
				'post_date'    => esc_html__( 'Post Date', 'elementor-dynamic-toolkit' ),
				'post_author'  => esc_html__( 'Post Author', 'elementor-dynamic-toolkit' ),
			];
		}

		$keys = get_post_custom_keys( $object_id );
		$fields = [];
		foreach ( is_array( $keys ) ? $keys : [] as $key ) {
			if ( ! str_starts_with( $key, '_' ) ) {
				$fields[ $key ] = $key;
			}
		}

		return $fields;
	}

	public function get_value( string $field, int $object_id = 0, array $options = [] ): mixed {
		$field = sanitize_key( $field );
		if ( $object_id <= 0 ) {
			$object_id = get_the_ID();
		}

		if ( ! $object_id ) {
			return null;
		}

		if ( in_array( $field, [ 'post_title', 'post_excerpt', 'post_date', 'post_content' ], true ) ) {
			$post = get_post( $object_id );
			return $post ? ( $post->$field ?? null ) : null;
		}

		return get_post_meta( $object_id, $field, true );
	}

	public function format_value( mixed $value, array $options = [] ): string {
		if ( is_array( $value ) || is_object( $value ) ) {
			return wp_json_encode( $value ) ?: '';
		}

		return (string) $value;
	}
}