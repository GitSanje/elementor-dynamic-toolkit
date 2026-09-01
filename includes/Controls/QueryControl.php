<?php
/**
 * Shared query control definitions for Elementor widgets.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

use EDT\Support\Helpers;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class QueryControl {

	public static function add_query_controls( \Elementor\Widget_Base $widget, array $custom_config = [] ): void {
		$post_type_options = Helpers::get_post_type_options();
		$taxonomy_options  = Helpers::get_taxonomy_options( 'post' );

		// SECTION: QUERY SOURCE & SETTINGS
		$widget->start_controls_section(
			'query_section',
			[
				'label' => esc_html__( 'Query Configuration', 'elementor-dynamic-toolkit' ),
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
				'label'       => esc_html__( 'Posts Per Page', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 100,
				'default'     => 6,
				'step'        => 1,
				'placeholder' => 6,
			]
		);

		$widget->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order By', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'date'          => esc_html__( 'Date', 'elementor-dynamic-toolkit' ),
					'title'         => esc_html__( 'Title', 'elementor-dynamic-toolkit' ),
					'menu_order'    => esc_html__( 'Menu Order', 'elementor-dynamic-toolkit' ),
					'modified'      => esc_html__( 'Last Modified', 'elementor-dynamic-toolkit' ),
					'comment_count' => esc_html__( 'Comment Count', 'elementor-dynamic-toolkit' ),
					'rand'          => esc_html__( 'Random', 'elementor-dynamic-toolkit' ),
					'ID'            => esc_html__( 'Post ID', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'date',
			]
		);

		$widget->add_control(
			'order',
			[
				'label'   => esc_html__( 'Sort Order', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'DESC' => esc_html__( 'Descending (Z-A / Newest first)', 'elementor-dynamic-toolkit' ),
					'ASC'  => esc_html__( 'Ascending (A-Z / Oldest first)', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'DESC',
			]
		);

		$widget->add_control(
			'offset',
			[
				'label'       => esc_html__( 'Offset', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 100,
				'default'     => 0,
				'description' => esc_html__( 'Number of posts to displace or pass over.', 'elementor-dynamic-toolkit' ),
			]
		);

		$widget->end_controls_section();

		// SECTION: FILTERS
		$widget->start_controls_section(
			'query_filters_section',
			[
				'label' => esc_html__( 'Query Filters & Taxonomies', 'elementor-dynamic-toolkit' ),
			]
		);

		$widget->add_control(
			'taxonomy',
			[
				'label'       => esc_html__( 'Filter by Taxonomy', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array_merge( [ '' => esc_html__( 'All Taxonomies', 'elementor-dynamic-toolkit' ) ], $taxonomy_options ),
				'default'     => '',
			]
		);

		$widget->add_control(
			'taxonomy_value',
			[
				'label'       => esc_html__( 'Taxonomy Term (Slug)', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'e.g. featured, tech', 'elementor-dynamic-toolkit' ),
				'condition'   => [ 'taxonomy!' => '' ],
			]
		);

		$widget->add_control(
			'meta_key',
			[
				'label'       => esc_html__( 'Custom Field Meta Key', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'featured_post',
			]
		);

		$widget->add_control(
			'meta_value',
			[
				'label'       => esc_html__( 'Custom Field Meta Value', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => [ 'meta_key!' => '' ],
			]
		);

		$widget->add_control(
			'meta_compare',
			[
				'label'     => esc_html__( 'Comparison Operator', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'='        => esc_html__( 'Equals (=)', 'elementor-dynamic-toolkit' ),
					'!='       => esc_html__( 'Not Equals (!=)', 'elementor-dynamic-toolkit' ),
					'>'        => esc_html__( 'Greater Than (>)', 'elementor-dynamic-toolkit' ),
					'>='       => esc_html__( 'Greater or Equal (>=)', 'elementor-dynamic-toolkit' ),
					'<'        => esc_html__( 'Less Than (<)', 'elementor-dynamic-toolkit' ),
					'<='       => esc_html__( 'Less or Equal (<=)', 'elementor-dynamic-toolkit' ),
					'LIKE'     => esc_html__( 'Contains (LIKE)', 'elementor-dynamic-toolkit' ),
					'NOT LIKE' => esc_html__( 'Does Not Contain (NOT LIKE)', 'elementor-dynamic-toolkit' ),
				],
				'default'   => '=',
				'condition' => [ 'meta_key!' => '' ],
			]
		);

		$widget->add_control(
			'search',
			[
				'label'       => esc_html__( 'Search Keyword', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Keyword...', 'elementor-dynamic-toolkit' ),
			]
		);

		$widget->add_control(
			'exclude_current',
			[
				'label'        => esc_html__( 'Exclude Current Post', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$widget->end_controls_section();

		// SECTION: EMPTY STATE CONFIGURATION
		$widget->start_controls_section(
			'empty_state_section',
			[
				'label' => esc_html__( 'Empty State', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'empty_title',
			[
				'label'       => esc_html__( 'Empty Title', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'No Results Found', 'elementor-dynamic-toolkit' ),
				'placeholder' => esc_html__( 'No results found', 'elementor-dynamic-toolkit' ),
			]
		);

		$widget->add_control(
			'empty_message',
			[
				'label'       => esc_html__( 'Empty Message', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'There are no items matching your criteria. Try adjusting your query or filters.', 'elementor-dynamic-toolkit' ),
			]
		);

		$widget->end_controls_section();
	}
}
