<?php
/**
 * Executes validated WordPress queries.
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
		private ?QueryProviderManager $provider_manager = null
	) {
		$this->cache ??= new QueryCache( new CacheService() );
		$this->provider_manager ??= new QueryProviderManager();
	}

	public function execute( array $args ): \WP_Query {
		$args = apply_filters( 'edt/query/args', $args );
		$provider = $this->provider_manager->resolve( $args );

		if ( null !== $provider ) {
			$args = ( new QueryValidator() )->validate( $provider->get_query_args( $args ) );
		}

		$cached = $this->cache->get( $args );

		if ( null !== $cached ) {
			$args['post__in'] = $cached['post_ids'] ?: [ 0 ];
			$args['orderby']  = 'post__in';
			$query = new \WP_Query( $args );
			$query->found_posts = (int) $cached['found_posts'];
		} else {
			$query = new \WP_Query( $args );
			$this->cache->set( $args, wp_list_pluck( $query->posts, 'ID' ), (int) $query->found_posts );
		}

		return apply_filters( 'edt/query/results', $query, $args );
	}
}