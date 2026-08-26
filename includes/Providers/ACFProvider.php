<?php
/**
 * Optional Advanced Custom Fields provider.
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
		return function_exists( 'get_field' ) && '' !== $field && null !== get_field( $field, $object_id );
	}

	public function get_fields( int $object_id ): array {
		return function_exists( 'get_fields' ) ? array_keys( (array) get_fields( $object_id ) ) : [];
	}

	public function get_value( string $field, int $object_id ) {
		return function_exists( 'get_field' ) ? get_field( $field, $object_id ) : null;
	}
}