<?php
/**
 * WordPress custom-field provider.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

final class CoreProvider implements DataProviderInterface {

	public function get_id(): string {
		return 'wordpress';
	}

	public function get_label(): string {
		return esc_html__( 'WordPress', 'elementor-dynamic-toolkit' );
	}

	public function supports( string $field, int $object_id ): bool {
		return '' !== $field && metadata_exists( 'post', $object_id, $field );
	}

	public function get_fields( int $object_id ): array {
		return array_keys( get_post_meta( $object_id ) );
	}

	public function get_value( string $field, int $object_id ) {
		return get_post_meta( $object_id, $field, true );
	}
}