<?php
namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

final class TaxonomyTag extends BaseTag {

	public function get_name(): string { return 'edt_taxonomy'; }
	public function get_title(): string { return esc_html__( 'Taxonomy', 'elementor-dynamic-toolkit' ); }

	protected function register_controls(): void {
		$this->add_control( 'taxonomy', [ 'label' => esc_html__( 'Taxonomy', 'elementor-dynamic-toolkit' ), 'type' => \Elementor\Controls_Manager::TEXT ] );
	}

	public function render(): void {
		$taxonomy = sanitize_key( (string) $this->get_settings( 'taxonomy' ) );
		$terms = $taxonomy ? get_the_terms( $this->get_post_id(), $taxonomy ) : false;
		$this->output_value( ! is_wp_error( $terms ) && ! empty( $terms ) ? implode( ', ', wp_list_pluck( $terms, 'name' ) ) : '' );
	}
}