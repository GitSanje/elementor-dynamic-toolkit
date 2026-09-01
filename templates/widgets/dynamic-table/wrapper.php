<?php
/**
 * Dynamic Table Wrapper Template.
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
			'empty_icon'    => $settings['empty_icon'] ?? 'eicon-table',
		]
	);
	return;
}
?>
<div class="edt-widget edt-dynamic-table-wrapper" data-widget-id="<?php echo esc_attr( $settings['widget_id'] ?? '' ); ?>">
	<div class="edt-table-responsive">
		<table class="edt-dynamic-table">
			<thead>
				<tr>
					<th scope="col" class="edt-table__col-title"><?php echo esc_html( $settings['title_header'] ?? esc_html__( 'Title', 'elementor-dynamic-toolkit' ) ); ?></th>
					<th scope="col" class="edt-table__col-author"><?php echo esc_html( $settings['author_header'] ?? esc_html__( 'Author', 'elementor-dynamic-toolkit' ) ); ?></th>
					<th scope="col" class="edt-table__col-date"><?php echo esc_html( $settings['date_header'] ?? esc_html__( 'Date', 'elementor-dynamic-toolkit' ) ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $result->get_items() as $index => $item ) {
					$item_context = $context->with_item( $item, $index );
					$renderer->render( 'widgets/dynamic-table/row', [ 'context' => $item_context, 'renderer' => $renderer ] );
				}
				?>
			</tbody>
		</table>
	</div>
</div>
