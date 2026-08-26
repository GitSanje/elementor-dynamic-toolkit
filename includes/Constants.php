<?php
/**
 * Plugin constants.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT;

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'EDT_PLUGIN_FILE' ) ) {
	define( 'EDT_PLUGIN_FILE', dirname( __DIR__ ) . '/elementor-dynamic-toolkit.php' );
}

if ( ! defined( 'EDT_PLUGIN_DIR' ) ) {
	define( 'EDT_PLUGIN_DIR', plugin_dir_path( EDT_PLUGIN_FILE ) );
}

if ( ! defined( 'EDT_PLUGIN_URL' ) ) {
	define( 'EDT_PLUGIN_URL', plugin_dir_url( EDT_PLUGIN_FILE ) );
}

if ( ! defined( 'EDT_PLUGIN_BASENAME' ) ) {
	define( 'EDT_PLUGIN_BASENAME', plugin_basename( EDT_PLUGIN_FILE ) );
}

final class Constants {

	public const VERSION = '0.1.0';
	public const MINIMUM_ELEMENTOR_VERSION = '3.20.0';
	public const FILE = EDT_PLUGIN_FILE;
	public const DIR = EDT_PLUGIN_DIR;
	public const URL = EDT_PLUGIN_URL;
	public const BASENAME = EDT_PLUGIN_BASENAME;
	public const TEXT_DOMAIN = 'elementor-dynamic-toolkit';

	private function __construct() {}
}