<?php
/**
 * Elementor Dynamic Tags registration boundary.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

use EDT\DynamicTags\ACFFieldTag;
use EDT\DynamicTags\CustomFieldTag;
use EDT\DynamicTags\OptionFieldTag;
use EDT\DynamicTags\RelatedPostTag;
use EDT\DynamicTags\TaxonomyTag;

defined( 'ABSPATH' ) || exit;

final class DynamicTags {

	public function register(): void {
		if ( ! class_exists( '\\Elementor\\Core\\DynamicTags\\Manager' ) ) {
			return;
		}

		add_action(
			'elementor/dynamic_tags/register',
			static function ( $manager ): void {
				\EDT\DynamicTags\Manager::instance()->register( $manager );
			}
		);
	}
}