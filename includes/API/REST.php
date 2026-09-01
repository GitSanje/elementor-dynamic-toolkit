<?php
/**
 * REST API Manager for the toolkit.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\API;

use EDT\API\Endpoints\QueryEndpoint;
use EDT\API\Endpoints\SearchEndpoint;
use EDT\Constants;

defined( 'ABSPATH' ) || exit;

final class REST {

	public function register(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes(): void {
		register_rest_route(
			Constants::REST_NAMESPACE,
			'/query',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ new QueryEndpoint(), 'handle' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			Constants::REST_NAMESPACE,
			'/search',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ new SearchEndpoint(), 'handle' ],
				'permission_callback' => [ $this, 'editor_permission_check' ],
			]
		);

		// Legacy backward compatibility route
		register_rest_route(
			Constants::REST_NAMESPACE,
			'/search-related-posts',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ new SearchEndpoint(), 'handle' ],
				'permission_callback' => [ $this, 'editor_permission_check' ],
			]
		);
	}

	public function editor_permission_check(): bool {
		return current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' );
	}
}
