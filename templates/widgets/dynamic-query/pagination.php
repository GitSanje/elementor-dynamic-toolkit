<?php
/**
 * Dynamic Query Pagination Template.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var \EDT\Rendering\RenderContext $context
 */

$result   = $context->get_result();
$settings = $context->get_settings();

if ( ! $result || $result->get_total_pages() <= 1 ) {
	return;
}

$pagination_type = $settings['pagination_type'] ?? 'numbers';
$current_page    = $result->get_current_page();
$total_pages     = $result->get_total_pages();
?>
<nav class="edt-pagination edt-pagination--<?php echo esc_attr( $pagination_type ); ?>" aria-label="<?php esc_attr_e( 'Pagination', 'elementor-dynamic-toolkit' ); ?>">
	<?php if ( 'load_more' === $pagination_type ) : ?>
		<?php if ( $result->has_more() ) : ?>
			<button type="button" class="edt-pagination__load-more-btn" data-page="<?php echo esc_attr( $current_page + 1 ); ?>" data-max-page="<?php echo esc_attr( $total_pages ); ?>">
				<span class="edt-pagination__btn-text"><?php echo esc_html( $settings['load_more_text'] ?? esc_html__( 'Load More', 'elementor-dynamic-toolkit' ) ); ?></span>
				<span class="edt-pagination__spinner" aria-hidden="true"></span>
			</button>
		<?php endif; ?>
	<?php else : ?>
		<div class="edt-pagination__numbers">
			<?php
			echo paginate_links(
				[
					'base'      => esc_url_raw( add_query_arg( 'edt_page', '%#%' ) ),
					'format'    => '?edt_page=%#%',
					'current'   => max( 1, $current_page ),
					'total'     => $total_pages,
					'prev_text' => '&laquo; ' . esc_html__( 'Previous', 'elementor-dynamic-toolkit' ),
					'next_text' => esc_html__( 'Next', 'elementor-dynamic-toolkit' ) . ' &raquo;',
					'type'      => 'list',
				]
			);
			?>
		</div>
	<?php endif; ?>
</nav>
