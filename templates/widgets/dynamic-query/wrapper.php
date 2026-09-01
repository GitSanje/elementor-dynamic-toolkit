<?php
/**
 * Dynamic Query Wrapper Template.
 * Supports vertical list and compact grid layouts.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var \EDT\Rendering\RenderContext  $context
 * @var \EDT\Rendering\WidgetRenderer $renderer
 */

$settings = $context->get_settings();
$result   = $context->get_result();

if ( ! $result || ! $result->has_items() ) {
	$renderer->render(
		'widgets/empty-state',
		[
			'empty_title'   => $settings['empty_title'] ?? '',
			'empty_message' => $settings['empty_message'] ?? '',
			'empty_icon'    => $settings['empty_icon'] ?? 'eicon-search-results',
		]
	);
	return;
}

$layout_type = $settings['layout_type'] ?? 'list';
$layout_class = 'edt-dynamic-query--layout-' . sanitize_html_class( $layout_type );

// Inline CSS vars for compact grid — same fix as the post grid wrapper.
$inline_style = '';
if ( 'grid' === $layout_type ) {
	$cols_d       = (int) ( $settings['grid_columns'] ?? 3 );
	$cols_t       = (int) ( $settings['grid_columns_tablet'] ?? 2 );
	$cols_m       = (int) ( $settings['grid_columns_mobile'] ?? 1 );
	$inline_style = sprintf( '--edt-cols:%d; --edt-cols-t:%d; --edt-cols-m:%d;', $cols_d, $cols_t, $cols_m );
}
?>
<div class="edt-widget edt-dynamic-query <?php echo esc_attr( $layout_class ); ?>"
	data-layout="<?php echo esc_attr( $layout_type ); ?>"
	data-widget-id="<?php echo esc_attr( $settings['widget_id'] ?? '' ); ?>"
	<?php if ( $inline_style ) : ?>style="<?php echo esc_attr( $inline_style ); ?>"<?php endif; ?>>

	<div class="edt-dynamic-query__items">
		<?php
		foreach ( $result->get_items() as $index => $item ) {
			$item_context = $context->with_item( $item, $index );
			$renderer->render( 'widgets/dynamic-query/item', [ 'context' => $item_context, 'renderer' => $renderer ] );
		}
		?>
	</div>

	<?php if ( ! empty( $settings['show_pagination'] ) && 'yes' === $settings['show_pagination'] ) : ?>
		<?php $renderer->render( 'widgets/dynamic-query/pagination', [ 'context' => $context, 'renderer' => $renderer ] ); ?>
	<?php endif; ?>
</div>
