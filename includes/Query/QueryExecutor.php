<?php
/**
 * Executes validated WordPress queries.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

defined( 'ABSPATH' ) || exit;

final class QueryExecutor {

	public function execute( array $args ): \WP_Query {
		$args = apply_filters( 'edt/query/args', $args );
		$cache = new QueryCache();
		$cached = $cache->get( $args );

		if ( null !== $cached ) {
			$args['post__in'] = $cached['post_ids'] ?: [ 0 ];
			$args['orderby']  = 'post__in';
			$query = new \WP_Query( $args );
			$query->found_posts = (int) $cached['found_posts'];
		} else {
			$query = new \WP_Query( $args );
			$cache->set( $args, wp_list_pluck( $query->posts, 'ID' ), (int) $query->found_posts );
		}

		return apply_filters( 'edt/query/results', $query, $args );
	}
}