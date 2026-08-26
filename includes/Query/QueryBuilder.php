<?php
/**
 * Fluent builder for safe WordPress post queries.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

defined( 'ABSPATH' ) || exit;

final class QueryBuilder {

	private array $args = [];

	public function post_type( string $post_type ): self {
		$this->args['post_type'] = sanitize_key( $post_type );
		return $this;
	}

	public function posts_per_page( int $posts_per_page ): self {
		$this->args['posts_per_page'] = max( 1, absint( $posts_per_page ) );
		return $this;
	}

	public function order_by( string $order_by ): self {
		$this->args['orderby'] = sanitize_key( $order_by );
		return $this;
	}

	public function order( string $order ): self {
		$this->args['order'] = 'ASC' === strtoupper( $order ) ? 'ASC' : 'DESC';
		return $this;
	}

	public function taxonomy( string $taxonomy, string $value ): self {
		$this->args['taxonomy']       = sanitize_key( $taxonomy );
		$this->args['taxonomy_value'] = sanitize_text_field( $value );
		return $this;
	}

	public function meta( string $key, string $value, string $compare = '=' ): self {
		$this->args['meta_query'] = [
			'key'     => sanitize_key( $key ),
			'value'   => sanitize_text_field( $value ),
			'compare' => strtoupper( $compare ),
		];
		return $this;
	}

	public function author( int $author_id ): self {
		$this->args['author'] = absint( $author_id );
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

	public function offset( int $offset ): self {
		$this->args['offset'] = max( 0, absint( $offset ) );
		return $this;
	}

	public function pagination( int $page ): self {
		$this->args['paged'] = max( 1, absint( $page ) );
		return $this;
	}

	public function get_args(): array {
		return ( new QueryValidator() )->validate( $this->args );
	}

	public function get(): \WP_Query {
		return ( new QueryExecutor() )->execute( $this->get_args() );
	}

	public function reset(): self {
		$this->args = [];
		return $this;
	}
}