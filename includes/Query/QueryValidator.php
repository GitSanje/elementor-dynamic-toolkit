<?php
/**
 * Validation and sanitization for query arguments.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

use EDT\Support\Sanitizer;
use EDT\Support\Validator;

defined( 'ABSPATH' ) || exit;

final class QueryValidator {

	private const ALLOWED_ORDER_BY = [
		'date',
		'title',
		'menu_order',
		'modified',
		'rand',
		'ID',
		'comment_count',
		'meta_value',
		'meta_value_num',
		'post__in',
	];

	public function validate( array $args ): array {
		$post_type = Sanitizer::sanitize_post_type( $args['post_type'] ?? 'post' );

		$validated = [
			'post_type'      => $post_type,
			'posts_per_page' => min( 100, max( 1, absint( $args['posts_per_page'] ?? 6 ) ) ),
			'orderby'        => in_array( $args['orderby'] ?? 'date', self::ALLOWED_ORDER_BY, true ) ? ( $args['orderby'] ?? 'date' ) : 'date',
			'order'          => Sanitizer::sanitize_order( $args['order'] ?? 'DESC' ),
			'post_status'    => 'publish',
		];

		if ( isset( $args['author'] ) && $args['author'] > 0 ) {
			$validated['author'] = absint( $args['author'] );
		}

		if ( ! empty( $args['author__in'] ) && is_array( $args['author__in'] ) ) {
			$validated['author__in'] = Sanitizer::sanitize_int_array( $args['author__in'] );
		}

		if ( ! empty( $args['author__not_in'] ) && is_array( $args['author__not_in'] ) ) {
			$validated['author__not_in'] = Sanitizer::sanitize_int_array( $args['author__not_in'] );
		}

		if ( isset( $args['offset'] ) && $args['offset'] >= 0 ) {
			$validated['offset'] = max( 0, absint( $args['offset'] ) );
		}

		if ( isset( $args['paged'] ) && $args['paged'] > 0 ) {
			$validated['paged'] = max( 1, absint( $args['paged'] ) );
		}

		if ( ! empty( $args['s'] ) ) {
			$validated['s'] = sanitize_text_field( (string) $args['s'] );
		}

		if ( ! empty( $args['post__in'] ) && is_array( $args['post__in'] ) ) {
			$validated['post__in'] = Sanitizer::sanitize_int_array( $args['post__in'] );
		}

		if ( ! empty( $args['post__not_in'] ) && is_array( $args['post__not_in'] ) ) {
			$validated['post__not_in'] = Sanitizer::sanitize_int_array( $args['post__not_in'] );
		}

		if ( ! empty( $args['post_parent'] ) ) {
			$validated['post_parent'] = absint( $args['post_parent'] );
		}

		if ( ! empty( $args['meta_key'] ) ) {
			$validated['meta_key'] = sanitize_key( (string) $args['meta_key'] );
		}

		// Taxonomy Query
		if ( ! empty( $args['tax_query'] ) && is_array( $args['tax_query'] ) ) {
			$tax_query = $this->validate_tax_query( $args['tax_query'] );
			if ( ! empty( $tax_query ) ) {
				$validated['tax_query'] = $tax_query;
			}
		} elseif ( ! empty( $args['taxonomy'] ) && ! empty( $args['taxonomy_value'] ) ) {
			$taxonomy = sanitize_key( (string) $args['taxonomy'] );
			$terms    = is_array( $args['taxonomy_value'] ) ? array_map( 'sanitize_text_field', $args['taxonomy_value'] ) : sanitize_text_field( (string) $args['taxonomy_value'] );

			$validated['tax_query'] = [
				[
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $terms,
				],
			];
		}

		// Meta Query
		if ( ! empty( $args['meta_query'] ) && is_array( $args['meta_query'] ) ) {
			$meta_query = $this->validate_meta_query( $args['meta_query'] );
			if ( ! empty( $meta_query ) ) {
				$validated['meta_query'] = $meta_query;
			}
		}

		// Date Query
		if ( ! empty( $args['date_query'] ) && is_array( $args['date_query'] ) ) {
			$validated['date_query'] = array_map(
				static fn ( $date ) => is_array( $date ) ? array_intersect_key( $date, array_flip( [ 'year', 'month', 'week', 'day', 'after', 'before', 'inclusive', 'column' ] ) ) : [],
				$args['date_query']
			);
		}

		if ( isset( $args['ignore_sticky_posts'] ) ) {
			$validated['ignore_sticky_posts'] = (bool) $args['ignore_sticky_posts'];
		} else {
			$validated['ignore_sticky_posts'] = true;
		}

		return $validated;
	}

	private function validate_tax_query( array $tax_query ): array {
		$valid = [];
		$relation = strtoupper( (string) ( $tax_query['relation'] ?? 'AND' ) );
		$valid['relation'] = in_array( $relation, [ 'AND', 'OR' ], true ) ? $relation : 'AND';

		foreach ( $tax_query as $key => $clause ) {
			if ( 'relation' === $key || ! is_array( $clause ) ) {
				continue;
			}

			$taxonomy = sanitize_key( (string) ( $clause['taxonomy'] ?? '' ) );
			$field    = in_array( $clause['field'] ?? 'slug', [ 'slug', 'id', 'term_id', 'name' ], true ) ? $clause['field'] : 'slug';
			$operator = in_array( strtoupper( (string) ( $clause['operator'] ?? 'IN' ) ), [ 'IN', 'NOT IN', 'AND', 'EXISTS', 'NOT EXISTS' ], true ) ? strtoupper( (string) $clause['operator'] ) : 'IN';

			if ( ! empty( $taxonomy ) && ! empty( $clause['terms'] ) ) {
				$valid[] = [
					'taxonomy'         => $taxonomy,
					'field'            => $field,
					'terms'            => is_array( $clause['terms'] ) ? array_map( 'sanitize_text_field', $clause['terms'] ) : sanitize_text_field( (string) $clause['terms'] ),
					'operator'         => $operator,
					'include_children' => isset( $clause['include_children'] ) ? (bool) $clause['include_children'] : true,
				];
			}
		}

		return count( $valid ) > 1 ? $valid : ( isset( $valid[0] ) ? [ $valid[0] ] : [] );
	}

	private function validate_meta_query( array $meta_query ): array {
		$valid = [];
		$allowed_compare = [ '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'EXISTS', 'NOT EXISTS' ];

		$relation = strtoupper( (string) ( $meta_query['relation'] ?? 'AND' ) );
		$valid['relation'] = in_array( $relation, [ 'AND', 'OR' ], true ) ? $relation : 'AND';

		foreach ( $meta_query as $key => $clause ) {
			if ( 'relation' === $key || ! is_array( $clause ) ) {
				continue;
			}

			$meta_key = sanitize_key( (string) ( $clause['key'] ?? '' ) );
			$compare  = strtoupper( (string) ( $clause['compare'] ?? '=' ) );
			$compare  = in_array( $compare, $allowed_compare, true ) ? $compare : '=';
			$type     = in_array( strtoupper( (string) ( $clause['type'] ?? 'CHAR' ) ), [ 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED' ], true ) ? strtoupper( (string) $clause['type'] ) : 'CHAR';

			if ( ! empty( $meta_key ) ) {
				$valid[] = [
					'key'     => $meta_key,
					'value'   => $clause['value'] ?? '',
					'compare' => $compare,
					'type'    => $type,
				];
			}
		}

		return count( $valid ) > 1 ? $valid : ( isset( $valid[0] ) ? [ $valid[0] ] : [] );
	}
}