<?php
/**
 * Taxonomy List Item Template.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var \EDT\Rendering\RenderContext $context
 */

$term     = $context->get_current_item();
$settings = $context->get_settings();

if ( ! $term instanceof \WP_Term ) {
	return;
}

$link = get_term_link( $term );
$show_count = ( $settings['show_count'] ?? 'yes' ) === 'yes';
?>
<li class="edt-taxonomy-list__item">
	<a href="<?php echo esc_url( is_wp_error( $link ) ? '#' : $link ); ?>" class="edt-taxonomy-list__link">
		<span class="edt-taxonomy-list__name"><?php echo esc_html( $term->name ); ?></span>
		<?php if ( $show_count ) : ?>
			<span class="edt-taxonomy-list__count"><?php echo esc_html( (string) $term->count ); ?></span>
		<?php endif; ?>
	</a>
</li>
