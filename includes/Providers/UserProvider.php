<?php
/**
 * WordPress User & Author Data Provider.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

final class UserProvider implements DataProviderInterface {

	public function get_id(): string {
		return 'user';
	}

	public function get_label(): string {
		return esc_html__( 'User & Author Data', 'elementor-dynamic-toolkit' );
	}

	public function supports( string $field, int $object_id ): bool {
		return true;
	}

	public function get_fields( int $object_id = 0 ): array {
		return [
			'display_name' => esc_html__( 'Display Name', 'elementor-dynamic-toolkit' ),
			'user_email'   => esc_html__( 'Email Address', 'elementor-dynamic-toolkit' ),
			'user_login'   => esc_html__( 'Username / Login', 'elementor-dynamic-toolkit' ),
			'first_name'   => esc_html__( 'First Name', 'elementor-dynamic-toolkit' ),
			'last_name'    => esc_html__( 'Last Name', 'elementor-dynamic-toolkit' ),
			'description'  => esc_html__( 'User Bio / Description', 'elementor-dynamic-toolkit' ),
			'user_url'     => esc_html__( 'Website URL', 'elementor-dynamic-toolkit' ),
		];
	}

	public function get_value( string $field, int $object_id = 0, array $options = [] ): mixed {
		$field = sanitize_key( $field );
		$user_id = $object_id > 0 ? $object_id : ( function_exists( 'get_current_user_id' ) ? get_current_user_id() : 0 );

		if ( $user_id <= 0 ) {
			return null;
		}

		$user = get_userdata( $user_id );
		if ( ! $user instanceof \WP_User ) {
			return null;
		}

		if ( isset( $user->$field ) ) {
			return $user->$field;
		}

		return get_user_meta( $user_id, $field, true );
	}

	public function format_value( mixed $value, array $options = [] ): string {
		return is_scalar( $value ) ? (string) $value : '';
	}
}
