<?php
/**
 * Dynamic Cards Wrapper Template.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var \EDT\Rendering\RenderContext $context
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
			'empty_icon'    => $settings['empty_icon'] ?? 'eicon-posts-carousel',
		]
	);
	return;
}
?>
<div class="edt-widget edt-dynamic-cards" data-widget-id="<?php echo esc_attr( $settings['widget_id'] ?? '' ); ?>">
	<div class="edt-dynamic-cards__list">
		<?php
		foreach ( $result->get_items() as $index => $item ) {
			$item_context = $context->with_item( $item, $index );
			$renderer->render( 'widgets/dynamic-cards/item', [ 'context' => $item_context, 'renderer' => $renderer ] );
		}
		?>
	</div>
</div>
