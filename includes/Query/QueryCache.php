<?php
/**
 * WordPress object-cache adapter for query results.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

defined( 'ABSPATH' ) || exit;

final class QueryCache {

	private const GROUP = 'edt_queries';

	public function get( array $args ): ?array {
		$cached = wp_cache_get( $this->key( $args ), self::GROUP );
		return is_array( $cached ) ? $cached : null;
	}

	public function set( array $args, array $post_ids, int $found_posts ): void {
		wp_cache_set(
			$this->key( $args ),
			[ 'post_ids' => $post_ids, 'found_posts' => $found_posts ],
			self::GROUP,
			MINUTE_IN_SECONDS
		);
	}

	private function key( array $args ): string {
		return md5( wp_json_encode( $args ) );
	}
}