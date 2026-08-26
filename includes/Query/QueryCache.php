<?php
/**
 * WordPress object-cache adapter for query results.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

use EDT\Admin\Settings;
use EDT\Services\CacheService;

defined( 'ABSPATH' ) || exit;

final class QueryCache {

	private const GROUP = 'edt_queries';

	public function __construct( private ?CacheService $cache = null ) {
		$this->cache ??= new CacheService();
	}

	public function get( array $args ): ?array {
		if ( ! Settings::get_bool( 'enable_cache', true ) ) {
			return null;
		}

		$cached = $this->cache->get( $this->key( $args ), null, self::GROUP );
		return is_array( $cached ) ? $cached : null;
	}

	public function set( array $args, array $post_ids, int $found_posts ): void {
		if ( ! Settings::get_bool( 'enable_cache', true ) ) {
			return;
		}

		$ttl = (int) apply_filters( 'edt/query/cache_ttl', MINUTE_IN_SECONDS, $args );
		if ( $ttl < 1 ) {
			return;
		}

		$this->cache->set(
			$this->key( $args ),
			[ 'post_ids' => $post_ids, 'found_posts' => $found_posts ],
			$ttl,
			self::GROUP
		);
	}

	private function key( array $args ): string {
		$this->sort_recursive( $args );
		return md5( wp_json_encode( $args ) );
	}

	private function sort_recursive( array &$value ): void {
		ksort( $value );

		foreach ( $value as &$item ) {
			if ( is_array( $item ) ) {
				$this->sort_recursive( $item );
			}
		}
	}
}