<?php
/**
 * REST API helpers for async Elementor editor lookups.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\API;

defined( 'ABSPATH' ) || exit;

final class REST {

	public function register(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes(): void {
		register_rest_route(
			'edt/v1',
			'/search-related-posts',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'search_related_posts' ],
				'permission_callback' => [ $this, 'permission_check' ],
				'args'                => [
					'query' => [
						'required'          => false,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'post_type' => [
						'required'          => false,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_key',
					],
				],
			]
		);
	}

	public function permission_check(): bool {
		return current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' );
	}

	public function search_related_posts( \WP_REST_Request $request ): \WP_REST_Response {
		$query = sanitize_text_field( (string) $request->get_param( 'query' ) );
		$post_type = sanitize_key( (string) $request->get_param( 'post_type' ) );

		if ( '' === $query ) {
			return new \WP_REST_Response( [], 200 );
		}

		$args = [
			'post_type'      => $post_type ?: 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			's'              => $query,
			'no_found_rows'  => true,
		];

		$results = [];
		$query_obj = new \WP_Query( $args );

		if ( $query_obj->have_posts() ) {
			while ( $query_obj->have_posts() ) {
				$query_obj->the_post();
				$results[] = [
					'id'   => get_the_ID(),
					'text' => get_the_title(),
				];
			}
			wp_reset_postdata();
		}

		return new \WP_REST_Response( $results, 200 );
	}
}
