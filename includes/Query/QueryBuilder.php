<?php
/**
 * Fluent builder for safe WordPress post queries.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

use EDT\Support\Sanitizer;

defined( 'ABSPATH' ) || exit;

final class QueryBuilder {

	private array $args = [];

	public function __construct(
		private ?QueryValidator $validator = null,
		private ?QueryExecutor $executor = null
	) {
		$this->validator ??= new QueryValidator();
		$this->executor ??= new QueryExecutor( null, null, $this->validator );
	}

	public function post_type( string|array $post_type ): self {
		$this->args['post_type'] = is_array( $post_type ) ? array_map( 'sanitize_key', $post_type ) : sanitize_key( $post_type );
		return $this;
	}

	public function posts_per_page( int $posts_per_page ): self {
		$this->args['posts_per_page'] = max( 1, absint( $posts_per_page ) );
		return $this;
	}

	public function order_by( string $order_by ): self {
		$this->args['orderby'] = Sanitizer::sanitize_order_by( $order_by );
		return $this;
	}

	public function order( string $order ): self {
		$this->args['order'] = Sanitizer::sanitize_order( $order );
		return $this;
	}

	public function ascending(): self {
		return $this->order( 'ASC' );
	}

	public function descending(): self {
		return $this->order( 'DESC' );
	}

	public function taxonomy( string $taxonomy, string|array $value, string $operator = 'IN', string $field = 'slug' ): self {
		$taxonomy = sanitize_key( $taxonomy );
		$operator = strtoupper( $operator );
		$field    = sanitize_key( $field );

		if ( ! isset( $this->args['tax_query'] ) ) {
			$this->args['tax_query'] = [ 'relation' => 'AND' ];
		}

		$this->args['tax_query'][] = [
			'taxonomy' => $taxonomy,
			'field'    => $field ?: 'slug',
			'terms'    => is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : sanitize_text_field( (string) $value ),
			'operator' => in_array( $operator, [ 'IN', 'NOT IN', 'AND', 'EXISTS', 'NOT EXISTS' ], true ) ? $operator : 'IN',
		];

		// For legacy backward compatibility
		$this->args['taxonomy']       = $taxonomy;
		$this->args['taxonomy_value'] = is_array( $value ) ? implode( ',', $value ) : (string) $value;

		return $this;
	}

	public function where_taxonomy( string $taxonomy, string|array $terms, string $operator = 'IN', string $field = 'slug' ): self {
		return $this->taxonomy( $taxonomy, $terms, $operator, $field );
	}

	public function meta( string $key, mixed $value, string $compare = '=', string $type = 'CHAR' ): self {
		if ( ! isset( $this->args['meta_query'] ) ) {
			$this->args['meta_query'] = [ 'relation' => 'AND' ];
		}

		$this->args['meta_query'][] = [
			'key'     => sanitize_key( $key ),
			'value'   => is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : sanitize_text_field( (string) $value ),
			'compare' => strtoupper( $compare ),
			'type'    => strtoupper( $type ),
		];

		return $this;
	}

	public function where_meta( string $key, mixed $value, string $compare = '=', string $type = 'CHAR' ): self {
		return $this->meta( $key, $value, $compare, $type );
	}

	public function author( int $author_id ): self {
		$this->args['author'] = absint( $author_id );
		return $this;
	}

	public function authors( array $author_ids ): self {
		$this->args['author__in'] = Sanitizer::sanitize_int_array( $author_ids );
		return $this;
	}

	public function date( array $date_query ): self {
		$this->args['date_query'] = array_values( $date_query );
		return $this;
	}

	public function search( string $search ): self {
		$this->args['s'] = sanitize_text_field( $search );
		return $this;
	}

	/**
	 * Skip SQL_CALC_FOUND_ROWS — significant performance win when pagination is not needed.
	 */
	public function no_found_rows( bool $skip = true ): self {
		$this->args['no_found_rows'] = $skip;
		return $this;
	}

	/**
	 * Disable automatic post cache lazy loading (better to prime manually in bulk).
	 */
	public function no_cache( bool $no_cache = true ): self {
		if ( $no_cache ) {
			$this->args['update_post_meta_cache'] = false;
			$this->args['update_post_term_cache'] = false;
		}
		return $this;
	}

	public function offset( int $offset ): self {
		$this->args['offset'] = max( 0, absint( $offset ) );
		return $this;
	}

	public function paginate( int $page, int $per_page = 0 ): self {
		$this->args['paged'] = max( 1, absint( $page ) );
		if ( $per_page > 0 ) {
			$this->posts_per_page( $per_page );
		}
		return $this;
	}

	public function pagination( int $page ): self {
		return $this->paginate( $page );
	}

	public function include_ids( array $ids ): self {
		$this->args['post__in'] = Sanitizer::sanitize_int_array( $ids );
		return $this;
	}

	public function exclude_ids( array $ids ): self {
		$this->args['post__not_in'] = Sanitizer::sanitize_int_array( $ids );
		return $this;
	}

	public function parent( int $parent_id ): self {
		$this->args['post_parent'] = absint( $parent_id );
		return $this;
	}

	public function to_definition(): QueryDefinition {
		return new QueryDefinition( $this->get_args() );
	}

	public function get_args(): array {
		return $this->validator->validate( $this->args );
	}

	public function execute(): QueryResult {
		return $this->executor->execute( $this->get_args() );
	}

	/**
	 * Backward compatibility method returning \WP_Query.
	 */
	public function get(): \WP_Query {
		return $this->executor->execute_raw( $this->get_args() );
	}

	public function reset(): self {
		$this->args = [];
		return $this;
	}
}