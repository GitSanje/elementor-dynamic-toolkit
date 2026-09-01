<?php
/**
 * Dynamic Post Grid Wrapper Template.
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

$layout_style    = sanitize_html_class( $settings['layout_style'] ?? 'grid' );
$columns_desktop = (int) ( $settings['columns'] ?? 3 );
$columns_tablet  = (int) ( $settings['columns_tablet'] ?? 2 );
$columns_mobile  = (int) ( $settings['columns_mobile'] ?? 1 );
$masonry_class   = ( 'masonry' === $layout_style ) ? 'edt-dynamic-grid--masonry' : '';
$list_class      = ( 'list' === $layout_style ) ? 'edt-dynamic-grid--list' : '';

// Build CSS custom properties inline — this is the fix for the compact grid.
// CSS attribute-selector rules require the data attributes to be exact strings,
// which fails when Elementor appends responsive suffixes differently.
// Inline CSS variables are always reliable.
$inline_style = sprintf(
	'--edt-cols:%d; --edt-cols-t:%d; --edt-cols-m:%d;',
	$columns_desktop,
	$columns_tablet,
	$columns_mobile
);

// Prepare ordered element list for item template
$ordered_elements = $context->get_settings()['element_order'] ?? [];
?>
<div class="edt-widget edt-dynamic-grid <?php echo esc_attr( "$masonry_class $list_class" ); ?>"
	data-columns-desktop="<?php echo esc_attr( (string) $columns_desktop ); ?>"
	data-columns-tablet="<?php echo esc_attr( (string) $columns_tablet ); ?>"
	data-columns-mobile="<?php echo esc_attr( (string) $columns_mobile ); ?>"
	data-pagination="<?php echo esc_attr( $settings['pagination'] ?? 'none' ); ?>"
	data-widget-id="<?php echo esc_attr( $settings['widget_id'] ?? '' ); ?>"
	style="<?php echo esc_attr( $inline_style ); ?>">

	<div class="edt-dynamic-grid__container">
		<?php
		foreach ( $result->get_items() as $index => $item ) {
			$item_context = $context->with_item( $item, $index );
			$renderer->render( 'widgets/dynamic-post-grid/item', [ 'context' => $item_context, 'renderer' => $renderer ] );
		}
		?>
	</div>

	<?php if ( ! empty( $settings['pagination'] ) && 'none' !== $settings['pagination'] ) : ?>
		<?php $renderer->render( 'widgets/dynamic-post-grid/pagination', [ 'context' => $context, 'renderer' => $renderer ] ); ?>
	<?php endif; ?>
</div>
