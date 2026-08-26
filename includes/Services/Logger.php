<?php
/**
 * Basic logger for debug-only plugin diagnostics.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Services;

defined( 'ABSPATH' ) || exit;

final class Logger {

	public function log( string $message, array $context = [], string $level = 'info' ): void {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}

		$context_string = '';
		if ( ! empty( $context ) ) {
			$context_string = ' ' . wp_json_encode( $context );
		}

		error_log( '[EDT][' . strtoupper( $level ) . '] ' . $message . $context_string );
	}
}
