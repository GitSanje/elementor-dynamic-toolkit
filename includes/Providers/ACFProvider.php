<?php
/**
 * Advanced Custom Fields (ACF) Data Provider.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

final class ACFProvider implements DataProviderInterface {

	public function get_id(): string {
		return 'acf';
	}

	public function get_label(): string {
		return esc_html__( 'Advanced Custom Fields', 'elementor-dynamic-toolkit' );
	}

	public function supports( string $field, int $object_id ): bool {
		return function_exists( 'get_field' );
	}

	public function get_fields( int $object_id = 0 ): array {
		if ( ! function_exists( 'acf_get_field_groups' ) || ! function_exists( 'acf_get_fields' ) ) {
			return [];
		}

		$fields = [];
		$groups = acf_get_field_groups( $object_id > 0 ? [ 'post_id' => $object_id ] : [] );

		foreach ( is_array( $groups ) ? $groups : [] as $group ) {
			$group_fields = acf_get_fields( $group['key'] );
			foreach ( is_array( $group_fields ) ? $group_fields : [] as $field ) {
				$fields[ $field['name'] ] = $field['label'] . ' (' . $field['name'] . ')';
			}
		}

		return $fields;
	}

	public function get_value( string $field, int $object_id = 0, array $options = [] ): mixed {
		if ( ! function_exists( 'get_field' ) ) {
			return null;
		}

		$format = (bool) ( $options['format_value'] ?? true );
		return get_field( $field, $object_id > 0 ? $object_id : false, $format );
	}

	public function format_value( mixed $value, array $options = [] ): string {
		if ( is_array( $value ) ) {
			if ( isset( $value['url'] ) ) {
				return (string) $value['url'];
			}
			if ( isset( $value['label'] ) ) {
				return (string) $value['label'];
			}
			return implode( ', ', array_map( 'strval', $value ) );
		}

		if ( is_object( $value ) && isset( $value->post_title ) ) {
			return (string) $value->post_title;
		}

		return (string) ( $value ?? '' );
	}
}