<?php
/**
 * Dynamic Table Row Template.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var \EDT\Rendering\RenderContext $context
 */

$post = $context->get_current_item();

if ( ! $post instanceof \WP_Post ) {
	return;
}

$post_id   = $post->ID;
$permalink = get_permalink( $post_id );
$title     = get_the_title( $post_id );
$author    = get_the_author_meta( 'display_name', $post->post_author );
$date      = get_the_date( '', $post_id );
?>
<tr class="edt-table__row">
	<td class="edt-table__cell edt-table__cell--title">
		<a href="<?php echo esc_url( $permalink ); ?>" class="edt-table__link"><?php echo esc_html( $title ); ?></a>
	</td>
	<td class="edt-table__cell edt-table__cell--author">
		<?php echo esc_html( $author ); ?>
	</td>
	<td class="edt-table__cell edt-table__cell--date">
		<time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>"><?php echo esc_html( $date ); ?></time>
	</td>
</tr>
