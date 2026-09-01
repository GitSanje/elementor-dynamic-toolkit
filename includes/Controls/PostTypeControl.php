<?php
/**
 * Post Type Control Helper.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

use EDT\Support\Helpers;

defined( 'ABSPATH' ) || exit;

final class PostTypeControl {

	public static function options(): array {
		return Helpers::get_post_type_options();
	}
}
