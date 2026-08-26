<?php
namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

final class CustomFieldTag extends BaseTag {

	public function get_name(): string { return 'edt_custom_field'; }
	public function get_title(): string { return esc_html__( 'Custom Field', 'elementor-dynamic-toolkit' ); }

	protected function register_controls(): void {
		$this->add_control( 'field', [ 'label' => esc_html__( 'Field Name', 'elementor-dynamic-toolkit' ), 'type' => \Elementor\Controls_Manager::TEXT ] );
	}

	public function render(): void {
		$field = sanitize_key( (string) $this->get_settings( 'field' ) );
		$this->output_value( $field ? get_post_meta( $this->get_post_id(), $field, true ) : '' );
	}
}