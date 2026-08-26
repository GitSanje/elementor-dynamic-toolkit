<?php
/**
 * Elementor widget registration.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

use EDT\Widgets\ContentSwitcher\Widget as ContentSwitcherWidget;
use EDT\Widgets\DynamicCards\Widget as DynamicCardsWidget;
use EDT\Widgets\DynamicPostGrid\Widget as DynamicPostGridWidget;
use EDT\Widgets\DynamicQuery\Widget as DynamicQueryWidget;
use EDT\Widgets\DynamicTable\Widget as DynamicTableWidget;
use EDT\Widgets\TaxonomyList\Widget as TaxonomyListWidget;

defined( 'ABSPATH' ) || exit;

final class Widgets {

	public function register(): void {
		add_action(
			'elementor/widgets/register',
			static function ( $widgets_manager ): void {
				$widgets_manager->register( new DynamicQueryWidget() );
				$widgets_manager->register( new DynamicPostGridWidget() );
				$widgets_manager->register( new DynamicTableWidget() );
				$widgets_manager->register( new TaxonomyListWidget() );
				$widgets_manager->register( new DynamicCardsWidget() );
				$widgets_manager->register( new ContentSwitcherWidget() );
			}
		);
	}
}