<?php
/**
 * Plugin Name: Elementor Dynamic Toolkit
 * Plugin URI: https://example.com/elementor-dynamic-toolkit
 * Description: A sophisticated dynamic content, query builder, and conditional logic framework for Elementor.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.1
 * Author: Elementor Dynamic Toolkit Team
 * Text Domain: elementor-dynamic-toolkit
 * Domain Path: /languages
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'EDT_PLUGIN_FILE' ) ) {
	define( 'EDT_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'EDT_PLUGIN_DIR' ) ) {
	define( 'EDT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'EDT_PLUGIN_URL' ) ) {
	define( 'EDT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

require_once __DIR__ . '/includes/Constants.php';
require_once __DIR__ . '/includes/Autoloader.php';

\EDT\Autoloader::register();

\EDT\Plugin::instance()->run();