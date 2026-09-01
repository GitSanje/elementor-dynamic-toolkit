<?php
/**
 * Dynamic Cards Item Template.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var \EDT\Rendering\RenderContext $context
 */

$post     = $context->get_current_item();
$settings = $context->get_settings();

if ( ! $post instanceof \WP_Post ) {
	return;
}

$post_id   = $post->ID;
$permalink = get_permalink( $post_id );
$title     = get_the_title( $post_id );
$excerpt   = wp_trim_words( get_the_excerpt( $post_id ), (int) ( $settings['excerpt_length'] ?? 18 ) );
$date      = get_the_date( '', $post_id );
?>
<article class="edt-dynamic-cards__item">
	<div class="edt-dynamic-cards__inner">
		<div class="edt-dynamic-cards__header">
			<span class="edt-dynamic-cards__date"><?php echo esc_html( $date ); ?></span>
		</div>
		<h3 class="edt-dynamic-cards__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
		</h3>
		<div class="edt-dynamic-cards__excerpt">
			<p><?php echo esc_html( $excerpt ); ?></p>
		</div>
		<div class="edt-dynamic-cards__footer">
			<a href="<?php echo esc_url( $permalink ); ?>" class="edt-dynamic-cards__link">
				<?php echo esc_html__( 'Explore Details', 'elementor-dynamic-toolkit' ); ?> &rarr;
			</a>
		</div>
	</div>
</article>
