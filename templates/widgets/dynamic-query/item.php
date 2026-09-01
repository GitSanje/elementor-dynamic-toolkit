<?php
/**
 * Dynamic Query Item Template.
 * Renders elements in user-defined repeater order.
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
$img_size  = $settings['image_size'] ?? 'medium';
$thumbnail = get_the_post_thumbnail_url( $post_id, $img_size );
$date      = get_the_date( '', $post_id );
$excerpt   = wp_trim_words( get_the_excerpt( $post_id ), (int) ( $settings['excerpt_length'] ?? 15 ) );

// Title HTML tag (user-configurable).
$title_tag = in_array( $settings['title_html_tag'] ?? 'h3', [ 'h1','h2','h3','h4','h5','h6','div','span' ], true )
	? ( $settings['title_html_tag'] ?? 'h3' )
	: 'h3';

// Ordered element slugs from repeater.
$element_order_raw = $settings['element_order'] ?? [];
$ordered_elements  = [];
if ( ! empty( $element_order_raw ) ) {
	foreach ( $element_order_raw as $item ) {
		if ( ! empty( $item['element_key'] ) && ( $item['element_visible'] ?? 'yes' ) === 'yes' ) {
			$ordered_elements[] = $item['element_key'];
		}
	}
} else {
	$ordered_elements = [ 'image', 'meta', 'title', 'excerpt', 'button' ];
}
?>
<article class="edt-dynamic-query__item">
	<?php foreach ( $ordered_elements as $element ) : ?>
		<?php if ( 'image' === $element && ( $settings['show_image'] ?? 'yes' ) === 'yes' && ! empty( $thumbnail ) ) : ?>
			<div class="edt-dynamic-query__media">
				<a href="<?php echo esc_url( $permalink ); ?>" class="edt-dynamic-query__image-link" tabindex="-1" aria-hidden="true">
					<img src="<?php echo esc_url( $thumbnail ); ?>"
						alt="<?php echo esc_attr( $title ); ?>"
						class="edt-dynamic-query__image"
						loading="lazy"
						decoding="async" />
				</a>
			</div>
		<?php elseif ( 'meta' === $element && ( $settings['show_meta'] ?? 'yes' ) === 'yes' ) : ?>
			<div class="edt-dynamic-query__meta">
				<time class="edt-dynamic-query__date" datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>">
					<?php echo esc_html( $date ); ?>
				</time>
			</div>
		<?php elseif ( 'title' === $element ) : ?>
			<div class="edt-dynamic-query__content">
				<<?php echo esc_attr( $title_tag ); ?> class="edt-dynamic-query__title">
					<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
				</<?php echo esc_attr( $title_tag ); ?>>
			</div>
		<?php elseif ( 'excerpt' === $element && ( $settings['show_excerpt'] ?? 'yes' ) === 'yes' && ! empty( $excerpt ) ) : ?>
			<div class="edt-dynamic-query__excerpt">
				<p><?php echo esc_html( $excerpt ); ?></p>
			</div>
		<?php elseif ( 'button' === $element && ! empty( $settings['show_button'] ) && 'yes' === $settings['show_button'] ) : ?>
			<div class="edt-dynamic-query__action">
				<a href="<?php echo esc_url( $permalink ); ?>" class="edt-dynamic-query__button">
					<?php echo esc_html( $settings['button_text'] ?? esc_html__( 'Read More', 'elementor-dynamic-toolkit' ) ); ?>
				</a>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</article>
