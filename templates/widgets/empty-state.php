<?php
/**
 * Configurable Empty State Template.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

$heading = ! empty( $empty_title ) ? $empty_title : esc_html__( 'No Results Found', 'elementor-dynamic-toolkit' );
$message = ! empty( $empty_message ) ? $empty_message : esc_html__( 'There are no items matching your criteria. Try adjusting your query or filters.', 'elementor-dynamic-toolkit' );
$icon    = ! empty( $empty_icon ) ? $empty_icon : 'eicon-search-results';
?>
<div class="edt-empty-state">
	<div class="edt-empty-state__icon">
		<i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
	</div>
	<h4 class="edt-empty-state__title"><?php echo esc_html( $heading ); ?></h4>
	<p class="edt-empty-state__description"><?php echo esc_html( $message ); ?></p>
</div>
