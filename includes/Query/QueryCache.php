<?php
/**
 * WordPress object-cache adapter for query results.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

use EDT\Admin\Settings;
use EDT\Constants;
use EDT\Services\CacheService;

defined( 'ABSPATH' ) || exit;

final class QueryCache {

	private const GROUP = Constants::CACHE_GROUP;

	private static bool $invalidation_hooked = false;

	public function __construct( private ?CacheService $cache = null ) {
		$this->cache ??= new CacheService();
		self::init_invalidation_hooks();
	}

	public static function init_invalidation_hooks(): void {
		if ( self::$invalidation_hooked ) {
			return;
		}

		if ( function_exists( 'add_action' ) ) {
			add_action( 'save_post', [ self::class, 'invalidate_all' ], 10, 0 );
			add_action( 'deleted_post', [ self::class, 'invalidate_all' ], 10, 0 );
			add_action( 'trash_post', [ self::class, 'invalidate_all' ], 10, 0 );
			add_action( 'clean_post_cache', [ self::class, 'invalidate_all' ], 10, 0 );
		}

		self::$invalidation_hooked = true;
	}

	public static function invalidate_all(): void {
		( new CacheService() )->flush_group( self::GROUP );
	}

	public function get( array $args ): ?array {
		if ( ! Settings::get_bool( 'enable_cache', true ) ) {
			return null;
		}

		if ( $this->is_user_specific( $args ) ) {
			return null;
		}

		$cached = $this->cache->get( $this->key( $args ), null, self::GROUP );
		return is_array( $cached ) ? $cached : null;
	}

	public function set( array $args, array $post_ids, int $found_posts, int $max_num_pages = 1 ): void {
		if ( ! Settings::get_bool( 'enable_cache', true ) ) {
			return;
		}

		if ( $this->is_user_specific( $args ) ) {
			return;
		}

		$ttl = (int) apply_filters( 'edt/query/cache_ttl', 5 * MINUTE_IN_SECONDS, $args );
		if ( $ttl < 1 ) {
			return;
		}

		$this->cache->set(
			$this->key( $args ),
			[
				'post_ids'      => $post_ids,
				'found_posts'   => $found_posts,
				'max_num_pages' => $max_num_pages,
			],
			$ttl,
			self::GROUP
		);
	}

	public function is_user_specific( array $args ): bool {
		// Avoid caching private or user-specific queries in public cache
		if ( isset( $args['post_status'] ) && 'publish' !== $args['post_status'] ) {
			return true;
		}

		if ( isset( $args['author'] ) && $args['author'] === 'current_user' ) {
			return true;
		}

		if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() && ! empty( $args['user_specific'] ) ) {
			return true;
		}

		return false;
	}

	public function key( array $args ): string {
		$this->sort_recursive( $args );
		$prefix = apply_filters( 'edt/cache/key_prefix', 'edt_q_' );
		return $prefix . md5( (string) wp_json_encode( $args ) );
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