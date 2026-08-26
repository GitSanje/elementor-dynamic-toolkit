<?php
/**
 * Shared base for query-driven Elementor widgets.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets;

use EDT\Query\QueryBuilder;

defined( 'ABSPATH' ) || exit;

abstract class AbstractQueryWidget extends \Elementor\Widget_Base {

	protected function get_query_settings( array $settings ): array {
		$defaults = [
			'post_type'      => 'post',
			'posts_per_page' => 6,
			'orderby'        => 'date',
			'order'          => 'DESC',
		];

		$settings = wp_parse_args( $settings, $defaults );

		$builder = new QueryBuilder();
		$builder->post_type( (string) $settings['post_type'] )
			->posts_per_page( absint( $settings['posts_per_page'] ) )
			->order_by( (string) $settings['orderby'] )
			->order( (string) $settings['order'] );

		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['taxonomy_value'] ) ) {
			$builder->taxonomy( (string) $settings['taxonomy'], (string) $settings['taxonomy_value'] );
		}

		if ( ! empty( $settings['author'] ) ) {
			$builder->author( absint( $settings['author'] ) );
		}

		if ( ! empty( $settings['search'] ) ) {
			$builder->search( (string) $settings['search'] );
		}

		return $builder->get_args();
	}

	protected function render_no_posts_notice(): void {
		echo '<p class="edt-query-empty">' . esc_html__( 'No posts found.', 'elementor-dynamic-toolkit' ) . '</p>';
	}
}
