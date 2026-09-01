<?php
/**
 * Taxonomy List Wrapper Template.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var \EDT\Rendering\RenderContext $context
 * @var \EDT\Rendering\WidgetRenderer $renderer
 */

$settings = $context->get_settings();
$taxonomy = sanitize_key( (string) ( $settings['taxonomy'] ?? 'category' ) );

if ( empty( $taxonomy ) || ( function_exists( 'taxonomy_exists' ) && ! taxonomy_exists( $taxonomy ) ) ) {
	$renderer->render(
		'widgets/empty-state',
		[
			'empty_title'   => esc_html__( 'No Taxonomy Selected', 'elementor-dynamic-toolkit' ),
			'empty_message' => esc_html__( 'Please select a valid taxonomy in the widget settings.', 'elementor-dynamic-toolkit' ),
			'empty_icon'    => 'eicon-tags',
		]
	);
	return;
}

$terms = get_terms(
	[
		'taxonomy'   => $taxonomy,
		'hide_empty' => ( $settings['hide_empty'] ?? 'yes' ) === 'yes',
	]
);

if ( is_wp_error( $terms ) || empty( $terms ) ) {
	$renderer->render(
		'widgets/empty-state',
		[
			'empty_title'   => esc_html__( 'No Terms Found', 'elementor-dynamic-toolkit' ),
			'empty_message' => esc_html__( 'No terms are currently available for the selected taxonomy.', 'elementor-dynamic-toolkit' ),
			'empty_icon'    => 'eicon-tags',
		]
	);
	return;
}

$layout_style = $settings['layout_style'] ?? 'list';
$wrapper_class = 'edt-taxonomy-list edt-taxonomy-list--' . sanitize_html_class( $layout_style );
?>
<div class="edt-widget <?php echo esc_attr( $wrapper_class ); ?>" data-widget-id="<?php echo esc_attr( $settings['widget_id'] ?? '' ); ?>">
	<ul class="edt-taxonomy-list__items">
		<?php
		foreach ( $terms as $index => $term ) {
			$item_context = $context->with_item( $term, $index );
			$renderer->render( 'widgets/taxonomy-list/item', [ 'context' => $item_context, 'renderer' => $renderer ] );
		}
		?>
	</ul>
</div>
