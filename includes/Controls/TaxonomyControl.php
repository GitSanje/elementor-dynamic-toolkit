<?php
/**
 * Taxonomy Control Helper.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

use EDT\Support\Helpers;

defined( 'ABSPATH' ) || exit;

final class TaxonomyControl {

	public static function options( string $post_type = 'post' ): array {
		return Helpers::get_taxonomy_options( $post_type );
	}
}
