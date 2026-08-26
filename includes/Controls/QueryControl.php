<?php
/**
 * Shared query control definitions for Elementor widgets.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class QueryControl {

	public static function add_query_controls( \Elementor\Widget_Base $widget ): void {
		$post_type_options = self::get_post_type_options();
		$taxonomy_options = self::get_taxonomy_options( 'post' );

		$widget->start_controls_section(
			'query_section',
			[
				'label' => esc_html__( 'Query', 'elementor-dynamic-toolkit' ),
			]
		);

		$widget->add_control(
			'post_type',
			[
				'label'   => esc_html__( 'Post Type', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post_type_options,
				'default' => 'post',
			]
		);

		$widget->add_control(
			'posts_per_page',
			[
				'label'      => esc_html__( 'Posts Per Page', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::NUMBER,
				'min'        => 1,
				'max'        => 100,
				'default'    => 6,
				'step'       => 1,
				'placeholder' => 6,
			]
		);

		$widget->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order By', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'date'       => esc_html__( 'Date', 'elementor-dynamic-toolkit' ),
					'title'      => esc_html__( 'Title', 'elementor-dynamic-toolkit' ),
					'menu_order' => esc_html__( 'Menu Order', 'elementor-dynamic-toolkit' ),
					'modified'   => esc_html__( 'Modified', 'elementor-dynamic-toolkit' ),
					'rand'       => esc_html__( 'Random', 'elementor-dynamic-toolkit' ),
					'ID'         => esc_html__( 'ID', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'date',
			]
		);

		$widget->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'DESC' => esc_html__( 'Descending', 'elementor-dynamic-toolkit' ),
					'ASC'  => esc_html__( 'Ascending', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'DESC',
			]
		);

		$widget->add_control(
			'taxonomy',
			[
				'label'       => esc_html__( 'Taxonomy', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $taxonomy_options,
				'placeholder' => esc_html__( 'All taxonomies', 'elementor-dynamic-toolkit' ),
				'condition'   => [ 'post_type!' => '' ],
			]
		);

		$widget->add_control(
			'taxonomy_value',
			[
				'label'       => esc_html__( 'Taxonomy Value (slug)', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'For example: featured', 'elementor-dynamic-toolkit' ),
				'condition'   => [ 'taxonomy!' => '' ],
			]
		);

		$widget->end_controls_section();
	}

	public static function get_post_type_options(): array {
		$options = [];
		foreach ( get_post_types( [ 'public' => true ], 'objects' ) as $post_type ) {
			$options[ $post_type->name ] = $post_type->labels->singular_name ?: $post_type->name;
		}

		return $options;
	}

	public static function get_taxonomy_options( string $post_type = 'post' ): array {
		$options = [];

		foreach ( get_object_taxonomies( $post_type, 'objects' ) as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->labels->singular_name ?: $taxonomy->name;
		}

		return $options;
	}
}
