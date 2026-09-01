<?php
/**
 * Dynamic Table Widget.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\DynamicTable;

use EDT\Controls\QueryControl;
use EDT\Widgets\AbstractQueryWidget;
use Elementor\Controls_Manager;

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

	protected function register_controls(): void {
		QueryControl::add_query_controls( $this );

		$this->start_controls_section(
			'table_headers_section',
			[
				'label' => esc_html__( 'Table Headers', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title_header',
			[
				'label'   => esc_html__( 'Title Column Header', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'elementor-dynamic-toolkit' ),
			]
		);

		$this->add_control(
			'author_header',
			[
				'label'   => esc_html__( 'Author Column Header', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Author', 'elementor-dynamic-toolkit' ),
			]
		);

		$this->add_control(
			'date_header',
			[
				'label'   => esc_html__( 'Date Column Header', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Date', 'elementor-dynamic-toolkit' ),
			]
		);

		$this->end_controls_section();

		$this->add_visibility_controls();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$result   = $this->execute_query( $settings );

		$this->render_template( 'widgets/dynamic-table/wrapper', $settings, $result );
	}
}
