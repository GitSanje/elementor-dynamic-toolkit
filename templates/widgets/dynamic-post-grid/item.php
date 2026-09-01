<?php
/**
 * Dynamic Post Grid Card Item Template.
 * Renders card elements in user-defined order via ElementOrderTrait repeater.
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
$img_size  = $settings['image_size'] ?? 'large';
$thumbnail = get_the_post_thumbnail_url( $post_id, $img_size );
$date      = get_the_date( '', $post_id );
$author    = get_the_author_meta( 'display_name', $post->post_author );
$excerpt   = wp_trim_words( get_the_excerpt( $post_id ), (int) ( $settings['excerpt_length'] ?? 20 ) );

// Badge taxonomy (user-configurable).
$badge_taxonomy = $settings['badge_taxonomy'] ?? 'category';
$terms          = get_the_terms( $post_id, $badge_taxonomy );
$badge          = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

// Meta items.
$meta_items = (array) ( $settings['meta_items'] ?? [ 'author', 'date' ] );

// Reading time estimate (200 wpm average).
$read_time = '';
if ( in_array( 'read_time', $meta_items, true ) ) {
	$word_count = str_word_count( strip_tags( get_post_field( 'post_content', $post_id ) ) );
	$read_time  = max( 1, (int) ceil( $word_count / 200 ) ) . ' ' . esc_html__( 'min read', 'elementor-dynamic-toolkit' );
}

// Title HTML tag (user-configurable).
$title_tag  = in_array( $settings['title_html_tag'] ?? 'h3', [ 'h1','h2','h3','h4','h5','h6','div','span' ], true )
	? ( $settings['title_html_tag'] ?? 'h3' )
	: 'h3';
$link_title = ( $settings['link_title'] ?? 'yes' ) === 'yes';

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
	$ordered_elements = [ 'image', 'badge', 'meta', 'title', 'excerpt', 'button' ];
}
?>
<article class="edt-dynamic-grid__item edt-card">
	<?php foreach ( $ordered_elements as $element ) : ?>
		<?php if ( 'image' === $element && ( $settings['show_image'] ?? 'yes' ) === 'yes' && ! empty( $thumbnail ) ) : ?>
			<div class="edt-card__media">
				<a href="<?php echo esc_url( $permalink ); ?>" class="edt-card__image-link" tabindex="-1" aria-hidden="true">
					<img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="edt-card__image" loading="lazy" decoding="async" />
				</a>
			</div>
		<?php elseif ( 'badge' === $element && ( $settings['show_badge'] ?? 'yes' ) === 'yes' && ! empty( $badge ) && ( $settings['show_image'] ?? 'yes' ) === 'yes' ) : ?>
			<div class="edt-card__badge-wrap">
				<span class="edt-card__badge"><?php echo esc_html( $badge ); ?></span>
			</div>
		<?php elseif ( 'meta' === $element && ( $settings['show_meta'] ?? 'yes' ) === 'yes' ) : ?>
			<div class="edt-card__meta">
				<?php if ( in_array( 'author', $meta_items, true ) ) : ?>
					<span class="edt-card__author"><svg aria-hidden="true" width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg> <?php echo esc_html( $author ); ?></span>
				<?php endif; ?>
				<?php if ( in_array( 'author', $meta_items, true ) && in_array( 'date', $meta_items, true ) ) : ?>
					<span class="edt-card__meta-separator" aria-hidden="true">&bull;</span>
				<?php endif; ?>
				<?php if ( in_array( 'date', $meta_items, true ) ) : ?>
					<time class="edt-card__date" datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>"><?php echo esc_html( $date ); ?></time>
				<?php endif; ?>
				<?php if ( in_array( 'comments', $meta_items, true ) ) : ?>
					<span class="edt-card__comments"><?php echo esc_html( get_comments_number( $post_id ) ); ?> <?php esc_html_e( 'comments', 'elementor-dynamic-toolkit' ); ?></span>
				<?php endif; ?>
				<?php if ( in_array( 'read_time', $meta_items, true ) && $read_time ) : ?>
					<span class="edt-card__read-time"><?php echo esc_html( $read_time ); ?></span>
				<?php endif; ?>
			</div>
		<?php elseif ( 'title' === $element ) : ?>
			<<?php echo esc_attr( $title_tag ); ?> class="edt-card__title">
				<?php if ( $link_title ) : ?>
					<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $title ); ?>
				<?php endif; ?>
			</<?php echo esc_attr( $title_tag ); ?>>
		<?php elseif ( 'excerpt' === $element && ( $settings['show_excerpt'] ?? 'yes' ) === 'yes' && ! empty( $excerpt ) ) : ?>
			<div class="edt-card__excerpt">
				<p><?php echo esc_html( $excerpt ); ?></p>
			</div>
		<?php elseif ( 'button' === $element && ( $settings['show_cta'] ?? 'yes' ) === 'yes' ) : ?>
			<div class="edt-card__footer">
				<a href="<?php echo esc_url( $permalink ); ?>" class="edt-card__button">
					<?php echo esc_html( $settings['cta_text'] ?? esc_html__( 'Read Article', 'elementor-dynamic-toolkit' ) ); ?>
					<span class="edt-card__button-arrow" aria-hidden="true">&rarr;</span>
				</a>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</article>
