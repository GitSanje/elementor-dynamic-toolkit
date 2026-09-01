<?php
/**
 * Dynamic Post Grid Widget — fully flexible, responsive, and customizable.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\DynamicPostGrid;

use EDT\Controls\QueryControl;
use EDT\Widgets\AbstractQueryWidget;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class Widget extends AbstractQueryWidget {

	/** All available card elements (slug => label). */
	public const ELEMENTS = [
		'image'   => 'Featured Image',
		'badge'   => 'Category Badge',
		'meta'    => 'Author & Date Meta',
		'title'   => 'Title',
		'excerpt' => 'Excerpt',
		'button'  => 'CTA Button',
	];

	public function get_name(): string {
		return 'edt_dynamic_post_grid';
	}

	public function get_title(): string {
		return esc_html__( 'Dynamic Post Grid', 'elementor-dynamic-toolkit' );
	}

	public function get_icon(): string {
		return 'eicon-posts-grid';
	}

	protected function register_controls(): void {

		// =====================================================================
		// CONTENT TAB
		// =====================================================================

		// --- Query Controls ---
		QueryControl::add_query_controls( $this );

		// --- Grid Layout Section ---
		$this->start_controls_section(
			'grid_layout_section',
			[
				'label' => esc_html__( 'Grid Layout', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout_style',
			[
				'label'   => esc_html__( 'Layout Style', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'    => esc_html__( 'Grid', 'elementor-dynamic-toolkit' ),
					'masonry' => esc_html__( 'Masonry', 'elementor-dynamic-toolkit' ),
					'list'    => esc_html__( 'Horizontal List', 'elementor-dynamic-toolkit' ),
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'elementor-dynamic-toolkit' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'condition'      => [ 'layout_style!' => 'list' ],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'      => esc_html__( 'Column Gap', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
				'default'    => [ 'size' => 28, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .edt-dynamic-grid__container' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'layout_style!' => 'list' ],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'      => esc_html__( 'Row Gap', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
				'default'    => [ 'size' => 28, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .edt-dynamic-grid__container' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'layout_style!' => 'list' ],
			]
		);

		// Content element toggles (kept for conditional style sections)
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
				'default'   => 'large',
				'options'   => [
					'thumbnail' => esc_html__( 'Thumbnail (150×150)', 'elementor-dynamic-toolkit' ),
					'medium'    => esc_html__( 'Medium (300×300)', 'elementor-dynamic-toolkit' ),
					'medium_large' => esc_html__( 'Medium Large (768px)', 'elementor-dynamic-toolkit' ),
					'large'     => esc_html__( 'Large (1024px)', 'elementor-dynamic-toolkit' ),
					'full'      => esc_html__( 'Full Size', 'elementor-dynamic-toolkit' ),
				],
				'condition' => [ 'show_image' => 'yes' ],
			]
		);

		$this->add_control(
			'show_badge',
			[
				'label'        => esc_html__( 'Category Badge', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [ 'show_image' => 'yes' ],
			]
		);

		$this->add_control(
			'badge_taxonomy',
			[
				'label'     => esc_html__( 'Badge Taxonomy', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'category',
				'options'   => [
					'category' => esc_html__( 'Category', 'elementor-dynamic-toolkit' ),
					'post_tag' => esc_html__( 'Tag', 'elementor-dynamic-toolkit' ),
				],
				'condition' => [ 'show_badge' => 'yes', 'show_image' => 'yes' ],
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label'        => esc_html__( 'Author & Date Meta', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'meta_items',
			[
				'label'       => esc_html__( 'Meta Items', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => [
					'author'   => esc_html__( 'Author', 'elementor-dynamic-toolkit' ),
					'date'     => esc_html__( 'Date', 'elementor-dynamic-toolkit' ),
					'comments' => esc_html__( 'Comment Count', 'elementor-dynamic-toolkit' ),
					'read_time' => esc_html__( 'Reading Time (est.)', 'elementor-dynamic-toolkit' ),
				],
				'default'     => [ 'author', 'date' ],
				'condition'   => [ 'show_meta' => 'yes' ],
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
				'default'   => 20,
				'condition' => [ 'show_excerpt' => 'yes' ],
			]
		);

		$this->add_control(
			'show_cta',
			[
				'label'        => esc_html__( 'Call to Action Button', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'cta_text',
			[
				'label'     => esc_html__( 'Button Label', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read Article', 'elementor-dynamic-toolkit' ),
				'condition' => [ 'show_cta' => 'yes' ],
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
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
				],
			]
		);

		$this->add_control(
			'link_title',
			[
				'label'        => esc_html__( 'Link Title to Post', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'pagination',
			[
				'label'     => esc_html__( 'Pagination Mode', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'options'   => [
					'none'      => esc_html__( 'None', 'elementor-dynamic-toolkit' ),
					'numbers'   => esc_html__( 'Page Numbers', 'elementor-dynamic-toolkit' ),
					'load_more' => esc_html__( 'AJAX Load More Button', 'elementor-dynamic-toolkit' ),
					'infinite'  => esc_html__( 'Infinite Scroll', 'elementor-dynamic-toolkit' ),
				],
				'default'   => 'none',
			]
		);

		$this->add_control(
			'load_more_label',
			[
				'label'     => esc_html__( 'Load More Button Text', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Load More Posts', 'elementor-dynamic-toolkit' ),
				'condition' => [ 'pagination' => [ 'load_more', 'infinite' ] ],
			]
		);

		$this->end_controls_section();

		// --- Element Order Section ---
		$this->register_order_controls( $this, self::ELEMENTS );

		// Visibility
		$this->add_visibility_controls();

		// =====================================================================
		// STYLE TAB
		// =====================================================================

		// --- Card Container ---
		$this->start_controls_section(
			'style_card',
			[
				'label' => esc_html__( 'Card Container', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .edt-card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .edt-card',
			]
		);

		$this->add_responsive_control(
			'card_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .edt-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'card_radius_important',
			[
				'label'     => esc_html__( 'Force Radius (!important)', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .edt-card' => 'border-radius: {{card_border_radius.TOP}}{{card_border_radius.UNIT}} {{card_border_radius.RIGHT}}{{card_border_radius.UNIT}} {{card_border_radius.BOTTOM}}{{card_border_radius.UNIT}} {{card_border_radius.LEFT}}{{card_border_radius.UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .edt-card',
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Card Body Padding', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .edt-card__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// --- Image Style ---
		$this->add_image_style_section( $this, 'grid', '.edt-card__image', '.edt-card__media' );

		// --- Badge Style ---
		$this->start_controls_section(
			'style_badge',
			[
				'label'     => esc_html__( 'Badge', 'elementor-dynamic-toolkit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_badge' => 'yes' ],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .edt-card__badge',
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label'     => esc_html__( 'Text Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .edt-card__badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .edt-card__badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .edt-card__badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'badge_radius_important',
			[
				'label'     => esc_html__( 'Force Radius (!important)', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .edt-card__badge' => 'border-radius: {{badge_border_radius.TOP}}{{badge_border_radius.UNIT}} {{badge_border_radius.RIGHT}}{{badge_border_radius.UNIT}} {{badge_border_radius.BOTTOM}}{{badge_border_radius.UNIT}} {{badge_border_radius.LEFT}}{{badge_border_radius.UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label'      => esc_html__( 'Padding', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .edt-card__badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// --- Meta Style ---
		$this->add_typography_style_section( $this, 'meta', '.edt-card__meta', esc_html__( 'Meta (Author / Date)', 'elementor-dynamic-toolkit' ) );

		// --- Title Style ---
		$this->add_typography_style_section( $this, 'title', '.edt-card__title, .edt-card__title a', esc_html__( 'Title', 'elementor-dynamic-toolkit' ) );
		$this->add_spacing_style_section( $this, 'title', '.edt-card__title', esc_html__( 'Title', 'elementor-dynamic-toolkit' ) );

		// --- Excerpt Style ---
		$this->add_typography_style_section( $this, 'excerpt', '.edt-card__excerpt, .edt-card__excerpt p', esc_html__( 'Excerpt', 'elementor-dynamic-toolkit' ) );
		$this->add_spacing_style_section( $this, 'excerpt', '.edt-card__excerpt', esc_html__( 'Excerpt', 'elementor-dynamic-toolkit' ) );

		// --- Button Style ---
		$this->add_button_style_section( $this, 'grid', '.edt-card__button' );
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$result   = $this->execute_query( $settings );

		$this->render_template( 'widgets/dynamic-post-grid/wrapper', $settings, $result );
	}
}
