<?php
/**
 * Dynamic Cards Widget.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\DynamicCards;

use EDT\Controls\QueryControl;
use EDT\Widgets\AbstractQueryWidget;
use Elementor\Controls_Manager;

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

	protected function register_controls(): void {
		QueryControl::add_query_controls( $this );

		$this->start_controls_section(
			'cards_display_section',
			[
				'label' => esc_html__( 'Card Settings', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'   => esc_html__( 'Excerpt Length', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 18,
			]
		);

		$this->end_controls_section();

		$this->add_visibility_controls();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$result   = $this->execute_query( $settings );

		$this->render_template( 'widgets/dynamic-cards/wrapper', $settings, $result );
	}
}
