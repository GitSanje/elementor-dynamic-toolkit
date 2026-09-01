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
				$widgets = [
					new DynamicQueryWidget(),
					new DynamicPostGridWidget(),
					new DynamicTableWidget(),
					new TaxonomyListWidget(),
					new DynamicCardsWidget(),
					new ContentSwitcherWidget(),
				];

				$widgets = apply_filters( 'edt/widgets', $widgets );

				foreach ( is_array( $widgets ) ? $widgets : [] as $widget ) {
					if ( is_object( $widget ) && method_exists( $widget, 'get_name' ) && method_exists( $widgets_manager, 'register' ) ) {
						$widgets_manager->register( $widget );
					}
				}
			}
		);
	}
}