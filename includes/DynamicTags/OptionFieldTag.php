<?php
namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

final class OptionFieldTag extends BaseTag {

	public function get_name(): string { return 'edt_option_field'; }
	public function get_title(): string { return esc_html__( 'Option Field', 'elementor-dynamic-toolkit' ); }

	protected function register_controls(): void {
		$this->add_control( 'option', [ 'label' => esc_html__( 'Option Name', 'elementor-dynamic-toolkit' ), 'type' => \Elementor\Controls_Manager::TEXT ] );
	}

	public function render(): void {
		$option = sanitize_key( (string) $this->get_settings( 'option' ) );
		$this->output_value( $option ? get_option( $option, '' ) : '' );
	}
}