<?php
/**
 * Content Switcher Wrapper Template.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var \EDT\Rendering\RenderContext $context
 */

$settings = $context->get_settings();
$content_a = $settings['content_a'] ?? '';
$content_b = $settings['content_b'] ?? '';
$label_a   = $settings['label_a'] ?? esc_html__( 'Option 1', 'elementor-dynamic-toolkit' );
$label_b   = $settings['label_b'] ?? esc_html__( 'Option 2', 'elementor-dynamic-toolkit' );
?>
<div class="edt-widget edt-content-switcher" data-widget-id="<?php echo esc_attr( $settings['widget_id'] ?? '' ); ?>">
	<div class="edt-content-switcher__nav" role="tablist">
		<button type="button" class="edt-content-switcher__btn is-active" data-target="content-a" role="tab" aria-selected="true">
			<?php echo esc_html( $label_a ); ?>
		</button>
		<button type="button" class="edt-content-switcher__btn" data-target="content-b" role="tab" aria-selected="false">
			<?php echo esc_html( $label_b ); ?>
		</button>
	</div>

	<div class="edt-content-switcher__panes">
		<div class="edt-content-switcher__pane is-active" id="content-a" role="tabpanel">
			<?php echo wp_kses_post( $content_a ); ?>
		</div>
		<div class="edt-content-switcher__pane" id="content-b" role="tabpanel" style="display: none;">
			<?php echo wp_kses_post( $content_b ); ?>
		</div>
	</div>
</div>
