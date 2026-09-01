<?php
/**
 * ACF Field Dynamic Tag.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

final class ACFFieldTag extends BaseTag {

	public function get_name(): string {
		return 'edt_acf_field';
	}

	public function get_title(): string {
		return esc_html__( 'ACF Dynamic Field', 'elementor-dynamic-toolkit' );
	}

	protected function register_controls(): void {
		$this->add_control(
			'field',
			[
				'label'       => esc_html__( 'ACF Field Name', 'elementor-dynamic-toolkit' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'subtitle, price, brand',
				'label_block' => true,
			]
		);

		$this->register_advanced_controls();
	}

	public function render(): void {
		$field = sanitize_key( (string) $this->get_settings( 'field' ) );
		if ( '' === $field ) {
			return;
		}

		$provider = $this->get_provider_manager()->get( 'acf' );
		$value = $provider ? $provider->get_value( $field, $this->get_post_id() ) : ( function_exists( 'get_field' ) ? get_field( $field, $this->get_post_id() ) : '' );

		$this->output_value( $value );
	}
}