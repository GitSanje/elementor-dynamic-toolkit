<?php
/**
 * Immutable Query Definition Value Object.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

defined( 'ABSPATH' ) || exit;

final class QueryDefinition {

	/**
	 * @param array<string, mixed> $args
	 */
	public function __construct( private readonly array $args ) {}

	public function get_args(): array {
		return $this->args;
	}

	public function get( string $key, mixed $default = null ): mixed {
		return $this->args[ $key ] ?? $default;
	}

	public function get_post_type(): string {
		return (string) ( $this->args['post_type'] ?? 'post' );
	}

	public function get_posts_per_page(): int {
		return (int) ( $this->args['posts_per_page'] ?? 6 );
	}

	public function get_page(): int {
		return (int) ( $this->args['paged'] ?? 1 );
	}

	public function get_offset(): int {
		return (int) ( $this->args['offset'] ?? 0 );
	}

	public function to_array(): array {
		return $this->args;
	}
}
