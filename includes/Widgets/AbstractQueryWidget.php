<?php
/**
 * Shared base for query-driven Elementor widgets.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets;

use EDT\Controls\VisibilityControl;
use EDT\Query\QueryBuilder;
use EDT\Query\QueryResult;
use EDT\Rendering\RenderContext;
use EDT\Rendering\WidgetRenderer;
use EDT\Widgets\Traits\ElementOrderTrait;
use EDT\Widgets\Traits\StyleControlsTrait;

defined( 'ABSPATH' ) || exit;

abstract class AbstractQueryWidget extends \Elementor\Widget_Base {

	use StyleControlsTrait;
	use ElementOrderTrait;

	protected ?WidgetRenderer $renderer = null;

	public function get_categories(): array {
		return [ \EDT\Elementor\Categories::SLUG ];
	}

	public function get_renderer(): WidgetRenderer {
		$this->renderer ??= new WidgetRenderer();
		return $this->renderer;
	}

	/**
	 * Determine if the widget settings have pagination enabled.
	 */
	protected function has_pagination( array $settings ): bool {
		if ( isset( $settings['pagination'] ) && ! in_array( $settings['pagination'], [ 'none', '' ], true ) ) {
			return true;
		}

		if ( isset( $settings['show_pagination'] ) && 'yes' === $settings['show_pagination'] ) {
			return true;
		}

		return false;
	}

	protected function build_query_builder( array $settings ): QueryBuilder {
		$builder = ( new QueryBuilder() )
			->post_type( (string) ( $settings['post_type'] ?? 'post' ) )
			->posts_per_page( absint( $settings['posts_per_page'] ?? 6 ) )
			->order_by( (string) ( $settings['orderby'] ?? 'date' ) )
			->order( (string) ( $settings['order'] ?? 'DESC' ) );

		// Skip SQL_CALC_FOUND_ROWS when pagination is off — major DB speedup.
		if ( ! $this->has_pagination( $settings ) ) {
			$builder->no_found_rows( true );
		}

		if ( isset( $settings['offset'] ) && $settings['offset'] > 0 ) {
			$builder->offset( absint( $settings['offset'] ) );
		}

		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['taxonomy_value'] ) ) {
			$builder->taxonomy( (string) $settings['taxonomy'], (string) $settings['taxonomy_value'] );
		}

		if ( ! empty( $settings['meta_key'] ) ) {
			$builder->meta(
				(string) $settings['meta_key'],
				$settings['meta_value'] ?? '',
				(string) ( $settings['meta_compare'] ?? '=' )
			);
		}

		if ( ! empty( $settings['search'] ) ) {
			$builder->search( (string) $settings['search'] );
		}

		if ( ! empty( $settings['exclude_current'] ) && 'yes' === $settings['exclude_current'] ) {
			$current_id = get_the_ID();
			if ( $current_id ) {
				$builder->exclude_ids( [ absint( $current_id ) ] );
			}
		}

		// Handle URL pagination parameter if present.
		$paged = isset( $_GET['edt_paged'] ) ? absint( $_GET['edt_paged'] ) : ( isset( $_GET['edt_page'] ) ? absint( $_GET['edt_page'] ) : 1 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $paged > 1 ) {
			$builder->paginate( $paged );
		}

		return $builder;
	}

	protected function execute_query( array $settings ): QueryResult {
		return $this->build_query_builder( $settings )->execute();
	}

	protected function add_visibility_controls(): void {
		VisibilityControl::add_controls( $this );
	}

	protected function render_template( string $template, array $settings, ?QueryResult $result = null ): void {
		$settings['widget_id'] = $this->get_id();
		$context = new RenderContext( $settings, $result );

		$this->get_renderer()->render(
			$template,
			[
				'context'  => $context,
				'renderer' => $this->get_renderer(),
			]
		);
	}
}
