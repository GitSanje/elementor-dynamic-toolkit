<?php
/**
 * Dynamic Query Elementor Widget — fully flexible, responsive, and customizable.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\DynamicQuery;

use EDT\Controls\QueryControl;
use EDT\Widgets\AbstractQueryWidget;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class Widget extends AbstractQueryWidget {

	/** All available list/grid elements (slug => label). */
	public const ELEMENTS = [
		'image'   => 'Featured Image',
		'meta'    => 'Date / Meta',
		'title'   => 'Title',
		'excerpt' => 'Excerpt',
		'button'  => 'Read More Button',
	];

	public function get_name(): string {
		return 'edt_dynamic_query';
	}

	public function get_title(): string {
		return esc_html__( 'Dynamic Query', 'elementor-dynamic-toolkit' );
	}

	public function get_icon(): string {
		return 'eicon-posts-list';
	}

	protected function register_controls(): void {

		// =====================================================================
		// CONTENT TAB
		// =====================================================================

		QueryControl::add_query_controls( $this );

		// --- Display & Layout Section ---
		$this->start_controls_section(
			'display_section',
			[
				'label' => esc_html__( 'Display & Layout', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout_type',
			[
				'label'   => esc_html__( 'Layout Format', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'list' => esc_html__( 'Vertical List', 'elementor-dynamic-toolkit' ),
					'grid' => esc_html__( 'Compact Grid', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'list',
			]
		);

		$this->add_responsive_control(
			'grid_columns',
			[
				'label'          => esc_html__( 'Grid Columns', 'elementor-dynamic-toolkit' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => '1', '2' => '2', '3' => '3',
					'4' => '4', '5' => '5', '6' => '6',
				],
				'condition'      => [ 'layout_type' => 'grid' ],
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label'      => esc_html__( 'Item Gap', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
				'default'    => [ 'size' => 16, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .edt-dynamic-query__items' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_image',
			[
				'label'        => esc_html__( 'Featured Image', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'image_size',
			[
				'label'     => esc_html__( 'Image Size', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'medium',
				'options'   => [
					'thumbnail'    => esc_html__( 'Thumbnail (150px)', 'elementor-dynamic-toolkit' ),
					'medium'       => esc_html__( 'Medium (300px)', 'elementor-dynamic-toolkit' ),
					'medium_large' => esc_html__( 'Medium Large (768px)', 'elementor-dynamic-toolkit' ),
					'large'        => esc_html__( 'Large (1024px)', 'elementor-dynamic-toolkit' ),
					'full'         => esc_html__( 'Full', 'elementor-dynamic-toolkit' ),
				],
				'condition' => [ 'show_image' => 'yes' ],
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label'        => esc_html__( 'Post Date / Meta', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'        => esc_html__( 'Excerpt', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'     => esc_html__( 'Excerpt Word Count', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 15,
				'condition' => [ 'show_excerpt' => 'yes' ],
			]
		);

		$this->add_control(
			'show_button',
			[
				'label'        => esc_html__( 'Read More Button', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'     => esc_html__( 'Button Label', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read More', 'elementor-dynamic-toolkit' ),
				'condition' => [ 'show_button' => 'yes' ],
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'separator' => 'before',
				'options'   => [
					'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
					'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
					'div' => 'div', 'span' => 'span',
				],
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label'        => esc_html__( 'Pagination', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'     => esc_html__( 'Pagination Type', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'numbers'   => esc_html__( 'Page Numbers', 'elementor-dynamic-toolkit' ),
					'load_more' => esc_html__( 'AJAX Load More', 'elementor-dynamic-toolkit' ),
				],
				'default'   => 'numbers',
				'condition' => [ 'show_pagination' => 'yes' ],
			]
		);

		$this->end_controls_section();

		// --- Element Order Section ---
		$this->register_order_controls( $this, self::ELEMENTS );

		$this->add_visibility_controls();

		// =====================================================================
		// STYLE TAB
		// =====================================================================

		// --- Item Container ---
		$this->start_controls_section(
			'style_item',
			[
				'label' => esc_html__( 'Item Container', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .edt-dynamic-query__item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'item_border',
				'selector' => '{{WRAPPER}} .edt-dynamic-query__item',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .edt-dynamic-query__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'item_radius_important',
			[
				'label'     => esc_html__( 'Force Radius (!important)', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .edt-dynamic-query__item' => 'border-radius: {{item_border_radius.TOP}}{{item_border_radius.UNIT}} {{item_border_radius.RIGHT}}{{item_border_radius.UNIT}} {{item_border_radius.BOTTOM}}{{item_border_radius.UNIT}} {{item_border_radius.LEFT}}{{item_border_radius.UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .edt-dynamic-query__item',
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label'      => esc_html__( 'Padding', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .edt-dynamic-query__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// --- Image Style ---
		$this->add_image_style_section( $this, 'dq', '.edt-dynamic-query__image', '.edt-dynamic-query__media' );

		// --- Meta Style ---
		$this->add_typography_style_section( $this, 'dq_meta', '.edt-dynamic-query__meta', esc_html__( 'Date / Meta', 'elementor-dynamic-toolkit' ) );

		// --- Title Style ---
		$this->add_typography_style_section( $this, 'dq_title', '.edt-dynamic-query__title, .edt-dynamic-query__title a', esc_html__( 'Title', 'elementor-dynamic-toolkit' ) );
		$this->add_spacing_style_section( $this, 'dq_title', '.edt-dynamic-query__title', esc_html__( 'Title', 'elementor-dynamic-toolkit' ) );

		// --- Excerpt Style ---
		$this->add_typography_style_section( $this, 'dq_excerpt', '.edt-dynamic-query__excerpt p', esc_html__( 'Excerpt', 'elementor-dynamic-toolkit' ) );

		// --- Button Style ---
		$this->add_button_style_section( $this, 'dq', '.edt-dynamic-query__button' );
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$result   = $this->execute_query( $settings );

		$this->render_template( 'widgets/dynamic-query/wrapper', $settings, $result );
	}
}