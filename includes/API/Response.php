<?php
/**
 * Standardized REST API Response Helper.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\API;

defined( 'ABSPATH' ) || exit;

final class Response {

	public static function success( mixed $data = [], int $status = 200, array $meta = [] ): \WP_REST_Response {
		$response = [
			'success' => true,
			'data'    => $data,
		];

		if ( ! empty( $meta ) ) {
			$response['meta'] = $meta;
		}

		return new \WP_REST_Response( $response, $status );
	}

	public static function error( string $message, string $code = 'error', int $status = 400, array $errors = [] ): \WP_REST_Response {
		$response = [
			'success' => false,
			'code'    => sanitize_key( $code ),
			'message' => esc_html( $message ),
		];

		if ( ! empty( $errors ) ) {
			$response['errors'] = $errors;
		}

		return new \WP_REST_Response( $response, $status );
	}
}
