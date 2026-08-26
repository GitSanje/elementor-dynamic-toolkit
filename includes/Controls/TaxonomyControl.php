<?php
/**
 * Taxonomy selector control helper.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

defined( 'ABSPATH' ) || exit;

final class TaxonomyControl {

	public static function options( string $post_type = 'post' ): array {
		return QueryControl::get_taxonomy_options( $post_type );
	}
}
