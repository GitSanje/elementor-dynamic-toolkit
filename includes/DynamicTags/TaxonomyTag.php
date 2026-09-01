<?php
/**
 * Taxonomy Terms Dynamic Tag.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

use EDT\Support\Helpers;

defined( 'ABSPATH' ) || exit;

final class TaxonomyTag extends BaseTag {

	public function get_name(): string {
		return 'edt_taxonomy';
	}

	public function get_title(): string {
		return esc_html__( 'Post Terms & Taxonomies', 'elementor-dynamic-toolkit' );
	}

	protected function register_controls(): void {
		$this->add_control(
			'taxonomy',
			[
				'label'   => esc_html__( 'Taxonomy', 'elementor-dynamic-toolkit' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => Helpers::get_taxonomy_options(),
				'default' => 'category',
			]
		);

		$this->add_control(
			'separator',
			[
				'label'   => esc_html__( 'Separator', 'elementor-dynamic-toolkit' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => ', ',
			]
		);

		$this->register_advanced_controls();
	}

	public function render(): void {
		$taxonomy = sanitize_key( (string) $this->get_settings( 'taxonomy' ) );
		if ( '' === $taxonomy ) {
			$taxonomy = 'category';
		}

		$separator = (string) ( $this->get_settings( 'separator' ) ?? ', ' );
		$terms     = get_the_terms( $this->get_post_id(), $taxonomy );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			$this->output_value( '' );
			return;
		}

		$names = wp_list_pluck( $terms, 'name' );
		$this->output_value( implode( $separator, $names ) );
	}
}