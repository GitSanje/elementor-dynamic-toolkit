<?php
namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

final class RelatedPostTag extends BaseTag {

	public function get_name(): string { return 'edt_related_post'; }
	public function get_title(): string { return esc_html__( 'Related Post', 'elementor-dynamic-toolkit' ); }

	protected function register_controls(): void {
		$this->add_control( 'field', [ 'label' => esc_html__( 'Relationship Field', 'elementor-dynamic-toolkit' ), 'type' => \Elementor\Controls_Manager::TEXT ] );
	}

	public function render(): void {
		$field = sanitize_key( (string) $this->get_settings( 'field' ) );
		$related_id = $field ? absint( get_post_meta( $this->get_post_id(), $field, true ) ) : 0;
		$this->output_value( $related_id ? get_the_title( $related_id ) : '' );
	}
}