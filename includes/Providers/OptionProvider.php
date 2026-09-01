<?php
/**
 * WordPress Site Options Data Provider.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

final class OptionProvider implements DataProviderInterface {

	private const DISALLOWED_OPTIONS = [
		'auth_key',
		'secure_auth_key',
		'logged_in_key',
		'nonce_key',
		'auth_salt',
		'secure_auth_salt',
		'logged_in_salt',
		'nonce_salt',
		'wp_user_roles',
	];

	public function get_id(): string {
		return 'option';
	}

	public function get_label(): string {
		return esc_html__( 'Site Options & Settings', 'elementor-dynamic-toolkit' );
	}

	public function supports( string $field, int $object_id ): bool {
		return ! in_array( sanitize_key( $field ), self::DISALLOWED_OPTIONS, true );
	}

	public function get_fields( int $object_id = 0 ): array {
		return [
			'blogname'        => esc_html__( 'Site Title (blogname)', 'elementor-dynamic-toolkit' ),
			'blogdescription' => esc_html__( 'Tagline (blogdescription)', 'elementor-dynamic-toolkit' ),
			'siteurl'         => esc_html__( 'WordPress Address (siteurl)', 'elementor-dynamic-toolkit' ),
			'home'            => esc_html__( 'Site Address (home)', 'elementor-dynamic-toolkit' ),
			'admin_email'     => esc_html__( 'Administration Email (admin_email)', 'elementor-dynamic-toolkit' ),
		];
	}

	public function get_value( string $field, int $object_id = 0, array $options = [] ): mixed {
		$field = sanitize_key( $field );
		if ( ! $this->supports( $field, $object_id ) ) {
			return null;
		}

		return get_option( $field, null );
	}

	public function format_value( mixed $value, array $options = [] ): string {
		if ( is_array( $value ) || is_object( $value ) ) {
			return wp_json_encode( $value ) ?: '';
		}

		return (string) ( $value ?? '' );
	}
}
