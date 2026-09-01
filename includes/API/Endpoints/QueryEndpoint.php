<?php
/**
 * REST Endpoint for AJAX Pagination & Query Execution.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\API\Endpoints;

use EDT\API\Response;
use EDT\Query\QueryBuilder;
use EDT\Rendering\RenderContext;
use EDT\Rendering\WidgetRenderer;

defined( 'ABSPATH' ) || exit;

final class QueryEndpoint {

	public function handle( \WP_REST_Request $request ): \WP_REST_Response {
		$params   = $request->get_json_params() ?: $request->get_params();
		$settings = is_array( $params['settings'] ?? null ) ? $params['settings'] : [];
		$page     = max( 1, absint( $params['page'] ?? 1 ) );
		$template = sanitize_text_field( (string) ( $params['template'] ?? 'widgets/dynamic-post-grid/item' ) );

		$builder = ( new QueryBuilder() )
			->post_type( (string) ( $settings['post_type'] ?? 'post' ) )
			->posts_per_page( absint( $settings['posts_per_page'] ?? 6 ) )
			->order_by( (string) ( $settings['orderby'] ?? 'date' ) )
			->order( (string) ( $settings['order'] ?? 'DESC' ) )
			->paginate( $page );

		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['taxonomy_value'] ) ) {
			$builder->taxonomy( (string) $settings['taxonomy'], (string) $settings['taxonomy_value'] );
		}

		if ( ! empty( $settings['search'] ) ) {
			$builder->search( (string) $settings['search'] );
		}

		if ( ! empty( $settings['meta_key'] ) ) {
			$builder->meta(
				(string) $settings['meta_key'],
				$settings['meta_value'] ?? '',
				(string) ( $settings['meta_compare'] ?? '=' )
			);
		}

		$result   = $builder->execute();
		$renderer = new WidgetRenderer();

		$html_items = [];
		$context    = new RenderContext( $settings, $result );

		foreach ( $result->get_items() as $index => $item ) {
			$item_context = $context->with_item( $item, $index );
			$html_items[] = $renderer->get_template_content( $template, [ 'context' => $item_context, 'renderer' => $renderer ] );
		}

		return Response::success(
			[
				'html'         => implode( '', $html_items ),
				'total'        => $result->get_total(),
				'current_page' => $result->get_current_page(),
				'total_pages'  => $result->get_total_pages(),
				'has_more'     => $result->has_more(),
			]
		);
	}
}
