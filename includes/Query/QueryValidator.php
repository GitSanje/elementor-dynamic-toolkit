<?php
/**
 * Validation and sanitization for query arguments.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

defined( 'ABSPATH' ) || exit;

final class QueryValidator {

	private const ORDER_BY = [ 'date', 'title', 'menu_order', 'modified', 'rand', 'ID' ];

	public function validate( array $args ): array {
		$post_type = sanitize_key( (string) ( $args['post_type'] ?? 'post' ) );
		$post_types = get_post_types( [ 'public' => true ] );

		if ( ! isset( $post_types[ $post_type ] ) ) {
			$post_type = 'post';
		}

		$validated = [
			'post_type'      => $post_type,
			'posts_per_page' => min( 100, max( 1, absint( $args['posts_per_page'] ?? 6 ) ) ),
			'orderby'        => in_array( $args['orderby'] ?? 'date', self::ORDER_BY, true ) ? ( $args['orderby'] ?? 'date' ) : 'date',
			'order'          => 'ASC' === strtoupper( (string) ( $args['order'] ?? 'DESC' ) ) ? 'ASC' : 'DESC',
		];

		if ( isset( $args['author'] ) ) {
			$validated['author'] = absint( $args['author'] );
		}

		if ( isset( $args['offset'] ) ) {
			$validated['offset'] = max( 0, absint( $args['offset'] ) );
		}

		if ( isset( $args['paged'] ) ) {
			$validated['paged'] = max( 1, absint( $args['paged'] ) );
		}

		if ( isset( $args['s'] ) ) {
			$validated['s'] = sanitize_text_field( (string) $args['s'] );
		}

		$taxonomy = sanitize_key( (string) ( $args['taxonomy'] ?? '' ) );
		$value    = sanitize_text_field( (string) ( $args['taxonomy_value'] ?? '' ) );

		if ( $taxonomy && $value && is_object_in_taxonomy( $post_type, $taxonomy ) ) {
			$validated['tax_query'] = [
				[
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $value,
				],
			];
		}

		if ( ! empty( $args['meta_query'] ) && is_array( $args['meta_query'] ) ) {
			$meta = $args['meta_query'];
			$compare = strtoupper( (string) ( $meta['compare'] ?? '=' ) );
			$allowed_compare = [ '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE' ];

			if ( ! empty( $meta['key'] ) && in_array( $compare, $allowed_compare, true ) ) {
				$validated['meta_query'] = [
					'key'     => sanitize_key( (string) $meta['key'] ),
					'value'   => sanitize_text_field( (string) ( $meta['value'] ?? '' ) ),
					'compare' => $compare,
				];
			}
		}

		if ( ! empty( $args['date_query'] ) && is_array( $args['date_query'] ) ) {
			$validated['date_query'] = array_map(
				static fn ( $date ) => is_array( $date ) ? array_intersect_key( $date, array_flip( [ 'year', 'month', 'week', 'day', 'after', 'before', 'inclusive' ] ) ) : [],
				$args['date_query']
			);
		}

		return $validated;
	}
}