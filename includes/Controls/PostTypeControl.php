<?php
/**
 * Post type selector control helper.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

defined( 'ABSPATH' ) || exit;

final class PostTypeControl {

	public static function options(): array {
		return QueryControl::get_post_type_options();
	}
}
