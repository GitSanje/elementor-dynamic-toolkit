<?php
/**
 * Dynamic cards widget.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\DynamicCards;

use EDT\Controls\QueryControl;
use EDT\Widgets\AbstractQueryWidget;

defined( 'ABSPATH' ) || exit;

final class Widget extends AbstractQueryWidget {

	public function get_name(): string {
		return 'edt_dynamic_cards';
	}

	public function get_title(): string {
		return esc_html__( 'Dynamic Cards', 'elementor-dynamic-toolkit' );
	}

	public function get_icon(): string {
		return 'eicon-posts-carousel';
	}

	public function get_categories(): array {
		return [ \EDT\Elementor\Categories::SLUG ];
	}

	protected function register_controls(): void {
		QueryControl::add_query_controls( $this );
		$this->add_visibility_controls();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$query = $this->execute_query( $settings );

		if ( ! $query->have_posts() ) {
			$this->render_no_posts_notice();
			return;
		}

		echo '<div class="edt-dynamic-cards">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<article class="edt-dynamic-cards__item">';
			echo '<h3 class="edt-dynamic-cards__title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
			echo '<div class="edt-dynamic-cards__excerpt">' . esc_html( wp_trim_words( get_the_excerpt(), 18 ) ) . '</div>';
			echo '</article>';
		}
		echo '</div>';
		wp_reset_postdata();
	}
}
