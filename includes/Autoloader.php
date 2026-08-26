<?php
/**
 * PSR-4-style autoloader for the EDT namespace.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT;

defined( 'ABSPATH' ) || exit;

final class Autoloader {

	private const NAMESPACE_PREFIX = 'EDT\\';

	private static bool $registered = false;

	public static function register(): void {
		if ( self::$registered ) {
			return;
		}

		spl_autoload_register( [ self::class, 'autoload' ] );
		self::$registered = true;
	}

	private static function autoload( string $class ): void {
		if ( 0 !== strpos( $class, self::NAMESPACE_PREFIX ) ) {
			return;
		}

		$relative_class = substr( $class, strlen( self::NAMESPACE_PREFIX ) );
		$file           = Constants::DIR . 'includes/' . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
}