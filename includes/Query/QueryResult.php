<?php
/**
 * Query Result Abstraction.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

defined( 'ABSPATH' ) || exit;

final class QueryResult {

	/**
	 * @param array<int, \WP_Post|object|array> $items
	 * @param array<string, mixed> $metadata
	 */
	public function __construct(
		private readonly array $items = [],
		private readonly int $total = 0,
		private readonly int $current_page = 1,
		private readonly int $total_pages = 1,
		private readonly array $metadata = [],
		private readonly ?\WP_Query $raw_query = null
	) {}

	public static function empty(): self {
		return new self( [], 0, 1, 0 );
	}

	public static function from_wp_query( \WP_Query $query, int $current_page = 1 ): self {
		$posts = is_array( $query->posts ) ? $query->posts : [];
		$total = (int) $query->found_posts;
		$max_num_pages = (int) $query->max_num_pages;
		if ( $max_num_pages < 1 && $total > 0 ) {
			$max_num_pages = 1;
		}

		return new self(
			$posts,
			$total,
			max( 1, $current_page ),
			$max_num_pages,
			[
				'post_count' => (int) $query->post_count,
			],
			$query
		);
	}

	public function get_items(): array {
		return $this->items;
	}

	public function get_total(): int {
		return $this->total;
	}

	public function get_current_page(): int {
		return $this->current_page;
	}

	public function get_total_pages(): int {
		return $this->total_pages;
	}

	public function has_more(): bool {
		return $this->current_page < $this->total_pages;
	}

	public function has_items(): bool {
		return ! empty( $this->items );
	}

	public function count(): int {
		return count( $this->items );
	}

	public function get_metadata(): array {
		return $this->metadata;
	}

	public function get_raw_query(): ?\WP_Query {
		return $this->raw_query;
	}
}
