<?php
/**
 * Executes validated WordPress queries and returns structured QueryResult.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Query;

use EDT\Providers\QueryProviderManager;
use EDT\Services\CacheService;

defined( 'ABSPATH' ) || exit;

final class QueryExecutor {

	public function __construct(
		private ?QueryCache $cache = null,
		private ?QueryProviderManager $provider_manager = null,
		private ?QueryValidator $validator = null
	) {
		$this->cache            ??= new QueryCache( new CacheService() );
		$this->provider_manager ??= new QueryProviderManager();
		$this->validator        ??= new QueryValidator();
	}

	public function execute( array $args ): QueryResult {
		$args     = (array) apply_filters( 'edt/query/args', $args );
		$provider = $this->provider_manager->resolve( $args );

		if ( null !== $provider ) {
			$args = $provider->get_query_args( $args );
		}

		$validated_args = $this->validator->validate( $args );
		$cached         = $this->cache->get( $validated_args );

		$current_page = (int) ( $validated_args['paged'] ?? 1 );

		if ( null !== $cached && ! empty( $cached['post_ids'] ) ) {
			$post_ids = (array) $cached['post_ids'];

			// Prime the full post object cache in one batch — avoids per-post DB hits.
			if ( function_exists( '_prime_post_caches' ) ) {
				_prime_post_caches( $post_ids, true, true );
			}

			$posts = [];
			foreach ( $post_ids as $pid ) {
				$post = get_post( $pid );
				if ( $post instanceof \WP_Post ) {
					$posts[] = $post;
				}
			}

			// Warm term + thumbnail caches for cached post ID set.
			$this->prime_secondary_caches( $posts );

			$found_posts   = (int) ( $cached['found_posts'] ?? count( $posts ) );
			$max_num_pages = (int) ( $cached['max_num_pages'] ?? 1 );

			$result = new QueryResult(
				$posts,
				$found_posts,
				$current_page,
				$max_num_pages,
				[ 'cached' => true ]
			);

			return apply_filters( 'edt/query/results', $result, $validated_args );
		}

		// Disable WP's own lazy cache loading — we prime everything manually below.
		$validated_args['update_post_meta_cache'] = false;
		$validated_args['update_post_term_cache']  = false;

		$query = new \WP_Query( $validated_args );

		// Bulk-prime all secondary caches (thumbnails, terms, authors) in 3 queries max.
		$this->prime_secondary_caches( (array) $query->posts );

		$post_ids = wp_list_pluck( (array) $query->posts, 'ID' );
		$this->cache->set( $validated_args, $post_ids, (int) $query->found_posts, (int) $query->max_num_pages );

		$result = QueryResult::from_wp_query( $query, $current_page );

		return apply_filters( 'edt/query/results', $result, $validated_args );
	}

	/**
	 * Primes thumbnail, term, and author caches in bulk for all posts.
	 * Eliminates N+1 queries inside widget render loops.
	 */
	private function prime_secondary_caches( array $posts ): void {
		if ( empty( $posts ) ) {
			return;
		}

		// 1. Thumbnail (post meta) cache — batched single query.
		if ( function_exists( 'update_post_thumbnail_cache' ) ) {
			$fake_query         = new \WP_Query();
			$fake_query->posts  = $posts;
			update_post_thumbnail_cache( $fake_query );
		}

		// 2. Term cache — batched single query.
		if ( function_exists( 'update_object_term_cache' ) ) {
			$post_ids   = wp_list_pluck( $posts, 'ID' );
			$post_types = array_unique( wp_list_pluck( $posts, 'post_type' ) );
			$taxonomies = [];
			foreach ( $post_types as $pt ) {
				$taxonomies = array_merge( $taxonomies, get_object_taxonomies( $pt ) );
			}
			if ( ! empty( $taxonomies ) ) {
				update_object_term_cache( $post_ids, array_unique( $taxonomies ) );
			}
		}

		// 3. Author (user) cache — batched.
		if ( function_exists( 'update_post_author_caches' ) ) {
			update_post_author_caches( $posts );
		}
	}

	/**
	 * Backward compatibility method returning raw WP_Query.
	 */
	public function execute_raw( array $args ): \WP_Query {
		$result = $this->execute( $args );
		if ( $result->get_raw_query() instanceof \WP_Query ) {
			return $result->get_raw_query();
		}

		return new \WP_Query( $this->validator->validate( $args ) );
	}
}