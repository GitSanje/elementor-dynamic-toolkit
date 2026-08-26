<?php
/**
 * Plugin Name: Elementor Dynamic Toolkit
 * Plugin URI: https://example.com/elementor-dynamic-toolkit
 * Description: A foundation for dynamic data and query-powered Elementor extensions.
 * Version: 0.1.0
 * Requires at least: 6.0
 * Requires PHP: 8.1
 * Author: SK
 * Text Domain: elementor-dynamic-toolkit
 * Domain Path: /languages
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/includes/Constants.php';
require_once __DIR__ . '/includes/Autoloader.php';

\EDT\Autoloader::register();

\EDT\Plugin::instance()->run();