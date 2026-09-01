<?php
/**
 * Custom Field Dynamic Tag.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

final class CustomFieldTag extends BaseTag {

	public function get_name(): string {
		return 'edt_custom_field';
	}

	public function get_title(): string {
		return esc_html__( 'Custom Post Field', 'elementor-dynamic-toolkit' );
	}

	protected function register_controls(): void {
		$this->add_control(
			'field',
			[
				'label'       => esc_html__( 'Field Key', 'elementor-dynamic-toolkit' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'price, custom_heading, etc.',
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

		$provider = $this->get_provider_manager()->get( 'core' );
		$value = $provider ? $provider->get_value( $field, $this->get_post_id() ) : get_post_meta( $this->get_post_id(), $field, true );

		$this->output_value( $value );
	}
}