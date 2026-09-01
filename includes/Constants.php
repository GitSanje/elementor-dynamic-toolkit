<?php
/**
 * Plugin Constants.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT;

defined( 'ABSPATH' ) || exit;

final class Constants {

	public const VERSION = '1.0.0';

	public const MINIMUM_ELEMENTOR_VERSION = '3.5.0';

	public const MINIMUM_PHP_VERSION = '8.1';

	public const TEXT_DOMAIN = 'elementor-dynamic-toolkit';

	public const SLUG = 'elementor-dynamic-toolkit';

	public const FILE = __DIR__ . '/../elementor-dynamic-toolkit.php';

	public const DIR = __DIR__ . '/../';

	public const URL = \EDT_PLUGIN_URL ?? '';

	public const BASENAME = 'elementor-dynamic-toolkit/elementor-dynamic-toolkit.php';

	public const CACHE_GROUP = 'edt_queries';

	public const DATA_CACHE_GROUP = 'edt_data';

	public const REST_NAMESPACE = 'edt/v1';
}