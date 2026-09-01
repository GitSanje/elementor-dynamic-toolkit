<?php
/**
 * Diagnostic logger for the toolkit.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Services;

use EDT\Admin\Settings;

defined( 'ABSPATH' ) || exit;

final class Logger {

	public function log( string $message, array $context = [], string $level = 'info' ): void {
		if ( ! Settings::get_bool( 'enable_debug', false ) ) {
			return;
		}

		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}

		$context_string = '';
		if ( ! empty( $context ) ) {
			$context_string = ' ' . wp_json_encode( $context );
		}

		error_log( '[EDT][' . strtoupper( sanitize_key( $level ) ) . '] ' . $message . $context_string );
	}

	public function debug( string $message, array $context = [] ): void {
		$this->log( $message, $context, 'debug' );
	}

	public function info( string $message, array $context = [] ): void {
		$this->log( $message, $context, 'info' );
	}

	public function warning( string $message, array $context = [] ): void {
		$this->log( $message, $context, 'warning' );
	}

	public function error( string $message, array $context = [] ): void {
		$this->log( $message, $context, 'error' );
	}
}
