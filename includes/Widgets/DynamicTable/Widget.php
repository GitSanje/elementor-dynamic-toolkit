<?php
/**
 * Dynamic table widget.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\DynamicTable;

use EDT\Controls\QueryControl;
use EDT\Widgets\AbstractQueryWidget;

defined( 'ABSPATH' ) || exit;

final class Widget extends AbstractQueryWidget {

	public function get_name(): string {
		return 'edt_dynamic_table';
	}

	public function get_title(): string {
		return esc_html__( 'Dynamic Table', 'elementor-dynamic-toolkit' );
	}

	public function get_icon(): string {
		return 'eicon-table';
	}

	public function get_categories(): array {
		return [ 'general' ];
	}

	protected function register_controls(): void {
		QueryControl::add_query_controls( $this );
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$args = $this->get_query_settings( $settings );
		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			$this->render_no_posts_notice();
			return;
		}

		echo '<table class="edt-dynamic-table">';
		echo '<thead><tr><th>' . esc_html__( 'Title', 'elementor-dynamic-toolkit' ) . '</th><th>' . esc_html__( 'Date', 'elementor-dynamic-toolkit' ) . '</th></tr></thead>';
		echo '<tbody>';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<tr>';
			echo '<td><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></td>';
			echo '<td>' . esc_html( get_the_date() ) . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
		wp_reset_postdata();
	}
}
