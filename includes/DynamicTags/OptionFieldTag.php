<?php
/**
 * Site Option Dynamic Tag.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

final class OptionFieldTag extends BaseTag {

	public function get_name(): string {
		return 'edt_option_field';
	}

	public function get_title(): string {
		return esc_html__( 'Site Option Field', 'elementor-dynamic-toolkit' );
	}

	protected function register_controls(): void {
		$this->add_control(
			'field',
			[
				'label'   => esc_html__( 'Option Name', 'elementor-dynamic-toolkit' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'blogname'        => esc_html__( 'Site Title', 'elementor-dynamic-toolkit' ),
					'blogdescription' => esc_html__( 'Site Tagline', 'elementor-dynamic-toolkit' ),
					'admin_email'     => esc_html__( 'Admin Email', 'elementor-dynamic-toolkit' ),
					'siteurl'         => esc_html__( 'Site URL', 'elementor-dynamic-toolkit' ),
					'home'            => esc_html__( 'Home URL', 'elementor-dynamic-toolkit' ),
					'custom'          => esc_html__( 'Custom Option Key', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'blogname',
			]
		);

		$this->add_control(
			'custom_field',
			[
				'label'       => esc_html__( 'Custom Option Key', 'elementor-dynamic-toolkit' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'condition'   => [ 'field' => 'custom' ],
				'placeholder' => 'my_custom_option',
			]
		);

		$this->register_advanced_controls();
	}

	public function render(): void {
		$field = sanitize_key( (string) $this->get_settings( 'field' ) );
		if ( 'custom' === $field ) {
			$field = sanitize_key( (string) $this->get_settings( 'custom_field' ) );
		}

		if ( '' === $field ) {
			return;
		}

		$provider = $this->get_provider_manager()->get( 'option' );
		$value = $provider ? $provider->get_value( $field ) : get_option( $field, '' );

		$this->output_value( $value );
	}
}