<?php
namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

final class ACFFieldTag extends BaseTag {

	public function get_name(): string { return 'edt_acf_field'; }
	public function get_title(): string { return esc_html__( 'ACF Field', 'elementor-dynamic-toolkit' ); }

	protected function register_controls(): void {
		$this->add_control( 'field', [ 'label' => esc_html__( 'ACF Field Name', 'elementor-dynamic-toolkit' ), 'type' => \Elementor\Controls_Manager::TEXT ] );
	}

	public function render(): void {
		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		$field = sanitize_key( (string) $this->get_settings( 'field' ) );
		$this->output_value( $field ? get_field( $field, $this->get_post_id() ) : '' );
	}
}