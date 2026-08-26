<?php
/**
 * Dynamic Query Elementor widget.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\DynamicQuery;

use EDT\Controls\QueryControl;
use EDT\Query\QueryBuilder;

defined( 'ABSPATH' ) || exit;

final class Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'edt_dynamic_query';
	}

	public function get_title() {
		return esc_html__( 'Dynamic Query', 'elementor-dynamic-toolkit' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {
		QueryControl::add_query_controls( $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$query    = ( new QueryBuilder() )
			->post_type( (string) ( $settings['post_type'] ?? 'post' ) )
			->posts_per_page( absint( $settings['posts_per_page'] ?? 6 ) )
			->order_by( (string) ( $settings['orderby'] ?? 'date' ) )
			->order( (string) ( $settings['order'] ?? 'DESC' ) );

		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['taxonomy_value'] ) ) {
			$query->taxonomy( $settings['taxonomy'], $settings['taxonomy_value'] );
		}

		$results = $query->get();

		if ( ! $results->have_posts() ) {
			echo '<p class="edt-dynamic-query__empty">' . esc_html__( 'No posts found.', 'elementor-dynamic-toolkit' ) . '</p>';
			return;
		}

		echo '<div class="edt-dynamic-query">';
		while ( $results->have_posts() ) {
			$results->the_post();
			echo '<article class="edt-dynamic-query__item">';
			echo '<h3 class="edt-dynamic-query__title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
			echo '</article>';
		}
		echo '</div>';
		wp_reset_postdata();
	}
}