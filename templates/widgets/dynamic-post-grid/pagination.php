<?php
/**
 * Dynamic Post Grid Pagination Template.
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

$pagination_mode = $settings['pagination'] ?? 'numbers';
$current_page    = $result->get_current_page();
$total_pages     = $result->get_total_pages();
?>
<div class="edt-grid-pagination edt-grid-pagination--<?php echo esc_attr( $pagination_mode ); ?>">
	<?php if ( in_array( $pagination_mode, [ 'load_more', 'infinite' ], true ) ) : ?>
		<?php if ( $result->has_more() ) : ?>
			<div class="edt-grid-pagination__action">
				<button type="button" class="edt-button edt-button--load-more"
					data-page="<?php echo esc_attr( $current_page + 1 ); ?>"
					data-max-page="<?php echo esc_attr( $total_pages ); ?>"
					data-mode="<?php echo esc_attr( $pagination_mode ); ?>">
					<span class="edt-button__text"><?php echo esc_html( $settings['load_more_label'] ?? esc_html__( 'Load More Posts', 'elementor-dynamic-toolkit' ) ); ?></span>
					<span class="edt-button__spinner" aria-hidden="true"></span>
				</button>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<div class="edt-grid-pagination__links">
			<?php
			echo paginate_links(
				[
					'base'      => esc_url_raw( add_query_arg( 'edt_paged', '%#%' ) ),
					'format'    => '?edt_paged=%#%',
					'current'   => max( 1, $current_page ),
					'total'     => $total_pages,
					'prev_text' => '&larr; ' . esc_html__( 'Previous', 'elementor-dynamic-toolkit' ),
					'next_text' => esc_html__( 'Next', 'elementor-dynamic-toolkit' ) . ' &rarr;',
					'type'      => 'list',
				]
			);
			?>
		</div>
	<?php endif; ?>
</div>
