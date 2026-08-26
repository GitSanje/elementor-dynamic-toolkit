<?php
/**
 * Elementor control for toolkit query selectors.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

defined( 'ABSPATH' ) || exit;

if ( class_exists( '\Elementor\Control_Select' ) ) {
	final class QuerySelectControl extends \Elementor\Control_Select {

		public const TYPE = 'edt_query_select';

		public function get_type() {
			return self::TYPE;
		}
	}
}