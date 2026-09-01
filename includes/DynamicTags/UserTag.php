<?php
/**
 * User / Author Dynamic Tag.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

final class UserTag extends BaseTag {

	public function get_name(): string {
		return 'edt_user';
	}

	public function get_title(): string {
		return esc_html__( 'User / Author Info', 'elementor-dynamic-toolkit' );
	}

	protected function register_controls(): void {
		$this->add_control(
			'user_source',
			[
				'label'   => esc_html__( 'Source', 'elementor-dynamic-toolkit' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'current_user' => esc_html__( 'Current Logged-in User', 'elementor-dynamic-toolkit' ),
					'post_author'  => esc_html__( 'Post Author', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'current_user',
			]
		);

		$this->add_control(
			'field',
			[
				'label'   => esc_html__( 'Field', 'elementor-dynamic-toolkit' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'display_name' => esc_html__( 'Display Name', 'elementor-dynamic-toolkit' ),
					'first_name'   => esc_html__( 'First Name', 'elementor-dynamic-toolkit' ),
					'last_name'    => esc_html__( 'Last Name', 'elementor-dynamic-toolkit' ),
					'user_email'   => esc_html__( 'Email Address', 'elementor-dynamic-toolkit' ),
					'user_url'     => esc_html__( 'Website URL', 'elementor-dynamic-toolkit' ),
					'description'  => esc_html__( 'Biographical Info', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'display_name',
			]
		);

		$this->register_advanced_controls();
	}

	public function render(): void {
		$source = sanitize_key( (string) $this->get_settings( 'user_source' ) );
		$field  = sanitize_key( (string) $this->get_settings( 'field' ) );

		$user_id = 0;
		if ( 'post_author' === $source ) {
			$post_id = $this->get_post_id();
			$user_id = $post_id ? (int) get_post_field( 'post_author', $post_id ) : 0;
		} else {
			$user_id = function_exists( 'get_current_user_id' ) ? get_current_user_id() : 0;
		}

		if ( $user_id <= 0 ) {
			$this->output_value( '' );
			return;
		}

		$provider = $this->get_provider_manager()->get( 'user' );
		$value = $provider ? $provider->get_value( $field, $user_id ) : '';

		$this->output_value( $value );
	}
}
