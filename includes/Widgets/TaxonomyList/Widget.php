<?php
/**
 * Taxonomy list widget.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\TaxonomyList;

defined( 'ABSPATH' ) || exit;

final class Widget extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'edt_taxonomy_list';
	}

	public function get_title(): string {
		return esc_html__( 'Taxonomy List', 'elementor-dynamic-toolkit' );
	}

	public function get_icon(): string {
		return 'eicon-tags';
	}

	public function get_categories(): array {
		return [ 'general' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'taxonomy_section',
			[
				'label' => esc_html__( 'Taxonomy', 'elementor-dynamic-toolkit' ),
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label'   => esc_html__( 'Taxonomy', 'elementor-dynamic-toolkit' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => \EDT\Controls\TaxonomyControl::options(),
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$taxonomy = sanitize_key( (string) $this->get_settings_for_display( 'taxonomy' ) );
		if ( '' === $taxonomy ) {
			echo '<p class="edt-taxonomy-list__empty">' . esc_html__( 'No taxonomy selected.', 'elementor-dynamic-toolkit' ) . '</p>';
			return;
		}

		$terms = get_terms(
			[
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
			]
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			echo '<p class="edt-taxonomy-list__empty">' . esc_html__( 'No terms found.', 'elementor-dynamic-toolkit' ) . '</p>';
			return;
		}

		echo '<ul class="edt-taxonomy-list">';
		foreach ( $terms as $term ) {
			echo '<li class="edt-taxonomy-list__item"><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></li>';
		}
		echo '</ul>';
	}
}
