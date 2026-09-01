<?php
/**
 * REST Endpoint for Editor Async Controls Search.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\API\Endpoints;

use EDT\API\Response;
use EDT\Providers\ProviderManager;

defined( 'ABSPATH' ) || exit;

final class SearchEndpoint {

	public function handle( \WP_REST_Request $request ): \WP_REST_Response {
		$source    = sanitize_key( (string) $request->get_param( 'source' ) ?: 'posts' );
		$query     = sanitize_text_field( (string) $request->get_param( 'query' ) );
		$post_type = sanitize_key( (string) $request->get_param( 'post_type' ) ?: 'post' );

		$results = [];

		if ( 'terms' === $source ) {
			$taxonomy = sanitize_key( (string) $request->get_param( 'taxonomy' ) ?: 'category' );
			$terms = get_terms(
				[
					'taxonomy'   => $taxonomy,
					'name__like' => $query,
					'number'     => 20,
					'hide_empty' => false,
				]
			);

			if ( ! is_wp_error( $terms ) && is_array( $terms ) ) {
				foreach ( $terms as $term ) {
					$results[] = [
						'id'   => $term->slug,
						'text' => $term->name . ' (' . $term->slug . ')',
					];
				}
			}
		} elseif ( 'users' === $source ) {
			$users = get_users(
				[
					'search' => '*' . $query . '*',
					'number' => 20,
				]
			);

			foreach ( $users as $user ) {
				$results[] = [
					'id'   => $user->ID,
					'text' => $user->display_name . ' (' . $user->user_email . ')',
				];
			}
		} elseif ( 'fields' === $source ) {
			$provider_id = sanitize_key( (string) $request->get_param( 'provider' ) ?: 'core' );
			$provider    = ( new ProviderManager() )->get( $provider_id );
			if ( $provider ) {
				$fields = $provider->get_fields();
				foreach ( $fields as $key => $label ) {
					if ( '' === $query || str_contains( strtolower( (string) $label ), strtolower( $query ) ) ) {
						$results[] = [
							'id'   => $key,
							'text' => $label,
						];
					}
				}
			}
		} else {
			// Posts search
			$posts = get_posts(
				[
					'post_type'      => $post_type,
					'post_status'    => 'publish',
					'posts_per_page' => 20,
					's'              => $query,
					'no_found_rows'  => true,
				]
			);

			foreach ( $posts as $post ) {
				$results[] = [
					'id'   => $post->ID,
					'text' => $post->post_title ?: esc_html__( '(No Title)', 'elementor-dynamic-toolkit' ),
				];
			}
		}

		return Response::success( $results );
	}
}
