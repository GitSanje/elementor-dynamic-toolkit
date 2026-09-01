<?php
/**
 * Reusable style-controls helpers for EDT widgets.
 *
 * Provides per-element style sections (typography, color, spacing, border, background)
 * with optional responsive mode and !important toggles — matching native Elementor widget UX.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

trait StyleControlsTrait {

	// -------------------------------------------------------------------------
	// Typography + Color combined (mirrors Elementor Heading widget pattern)
	// -------------------------------------------------------------------------

	/**
	 * Adds a full typography + color style section for a named element.
	 *
	 * @param Widget_Base $widget        The widget instance.
	 * @param string      $prefix        Control ID prefix (e.g. 'title', 'meta', 'excerpt').
	 * @param string      $selector      CSS selector relative to widget wrapper.
	 * @param string      $section_label Human-readable section label.
	 */
	protected function add_typography_style_section( Widget_Base $widget, string $prefix, string $selector, string $section_label ): void {
		$widget->start_controls_section(
			"style_{$prefix}",
			[
				'label' => $section_label,
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// --- Typography Group ---
		$widget->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => "{$prefix}_typography",
				'selector' => "{{WRAPPER}} {$selector}",
			]
		);

		// --- Color ---
		$widget->add_control(
			"{$prefix}_color",
			[
				'label'     => esc_html__( 'Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					"{{WRAPPER}} {$selector}" => 'color: {{VALUE}};',
				],
			]
		);

		// !important toggle for color
		$widget->add_control(
			"{$prefix}_color_important",
			[
				'label'       => esc_html__( 'Force Color (!important)', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Override theme CSS with !important on this color.', 'elementor-dynamic-toolkit' ),
				'selectors'   => [
					"{{WRAPPER}} {$selector}" => 'color: {{' . $prefix . '_color.VALUE}} !important;',
				],
				'condition'   => [ "{$prefix}_color!" => '' ],
			]
		);

		// --- Hover Color ---
		$widget->add_control(
			"{$prefix}_color_heading_hover",
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Hover State', 'elementor-dynamic-toolkit' ),
				'separator' => 'before',
			]
		);

		$widget->add_control(
			"{$prefix}_hover_color",
			[
				'label'     => esc_html__( 'Color on Hover', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					"{{WRAPPER}} {$selector}:hover, {{WRAPPER}} {$selector} a:hover" => 'color: {{VALUE}};',
				],
			]
		);

		// --- Text Shadow ---
		$widget->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => "{$prefix}_text_shadow",
				'selector' => "{{WRAPPER}} {$selector}",
			]
		);

		$widget->end_controls_section();
	}

	// -------------------------------------------------------------------------
	// Spacing (margin + padding) — responsive, all four sides
	// -------------------------------------------------------------------------

	/**
	 * Adds a combined margin + padding section with responsive controls + !important toggle.
	 */
	protected function add_spacing_style_section( Widget_Base $widget, string $prefix, string $selector, string $section_label ): void {
		$widget->start_controls_section(
			"style_{$prefix}_spacing",
			[
				'label' => $section_label . ' ' . esc_html__( 'Spacing', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$widget->add_responsive_control(
			"{$prefix}_margin",
			[
				'label'      => esc_html__( 'Margin', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%' ],
				'selectors'  => [
					"{{WRAPPER}} {$selector}" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_margin_important",
			[
				'label'       => esc_html__( 'Force Margin (!important)', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Override theme CSS with !important on margin.', 'elementor-dynamic-toolkit' ),
				'selectors'   => [
					"{{WRAPPER}} {$selector}" => 'margin: {{' . $prefix . '_margin.TOP}}{{' . $prefix . '_margin.UNIT}} {{' . $prefix . '_margin.RIGHT}}{{' . $prefix . '_margin.UNIT}} {{' . $prefix . '_margin.BOTTOM}}{{' . $prefix . '_margin.UNIT}} {{' . $prefix . '_margin.LEFT}}{{' . $prefix . '_margin.UNIT}} !important;',
				],
			]
		);

		$widget->add_responsive_control(
			"{$prefix}_padding",
			[
				'label'      => esc_html__( 'Padding', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%' ],
				'selectors'  => [
					"{{WRAPPER}} {$selector}" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_padding_important",
			[
				'label'       => esc_html__( 'Force Padding (!important)', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Override theme CSS with !important on padding.', 'elementor-dynamic-toolkit' ),
				'selectors'   => [
					"{{WRAPPER}} {$selector}" => 'padding: {{' . $prefix . '_padding.TOP}}{{' . $prefix . '_padding.UNIT}} {{' . $prefix . '_padding.RIGHT}}{{' . $prefix . '_padding.UNIT}} {{' . $prefix . '_padding.BOTTOM}}{{' . $prefix . '_padding.UNIT}} {{' . $prefix . '_padding.LEFT}}{{' . $prefix . '_padding.UNIT}} !important;',
				],
			]
		);

		$widget->end_controls_section();
	}

	// -------------------------------------------------------------------------
	// Border + Border-radius
	// -------------------------------------------------------------------------

	/**
	 * Adds border controls (type, width, color) + border-radius with !important toggle.
	 */
	protected function add_border_style_section( Widget_Base $widget, string $prefix, string $selector, string $section_label ): void {
		$widget->start_controls_section(
			"style_{$prefix}_border",
			[
				'label' => $section_label . ' ' . esc_html__( 'Border', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$widget->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => "{$prefix}_border",
				'selector' => "{{WRAPPER}} {$selector}",
			]
		);

		$widget->add_responsive_control(
			"{$prefix}_border_radius",
			[
				'label'      => esc_html__( 'Border Radius', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					"{{WRAPPER}} {$selector}" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_radius_important",
			[
				'label'       => esc_html__( 'Force Radius (!important)', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Override theme CSS with !important on border-radius.', 'elementor-dynamic-toolkit' ),
				'selectors'   => [
					"{{WRAPPER}} {$selector}" => 'border-radius: {{' . $prefix . '_border_radius.TOP}}{{' . $prefix . '_border_radius.UNIT}} {{' . $prefix . '_border_radius.RIGHT}}{{' . $prefix . '_border_radius.UNIT}} {{' . $prefix . '_border_radius.BOTTOM}}{{' . $prefix . '_border_radius.UNIT}} {{' . $prefix . '_border_radius.LEFT}}{{' . $prefix . '_border_radius.UNIT}} !important;',
				],
			]
		);

		$widget->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => "{$prefix}_box_shadow",
				'selector' => "{{WRAPPER}} {$selector}",
			]
		);

		$widget->end_controls_section();
	}

	// -------------------------------------------------------------------------
	// Background
	// -------------------------------------------------------------------------

	/**
	 * Adds background controls (solid color, gradient, image) for a selector.
	 */
	protected function add_background_style_section( Widget_Base $widget, string $prefix, string $selector, string $section_label ): void {
		$widget->start_controls_section(
			"style_{$prefix}_bg",
			[
				'label' => $section_label . ' ' . esc_html__( 'Background', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$widget->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => "{$prefix}_background",
				'types'    => [ 'classic', 'gradient' ],
				'selector' => "{{WRAPPER}} {$selector}",
			]
		);

		$widget->end_controls_section();
	}

	// -------------------------------------------------------------------------
	// Image-specific style controls
	// -------------------------------------------------------------------------

	/**
	 * Adds image-specific style controls: aspect ratio, object-fit, border-radius, opacity.
	 */
	protected function add_image_style_section( Widget_Base $widget, string $prefix, string $img_selector, string $wrapper_selector ): void {
		$widget->start_controls_section(
			"style_{$prefix}_image",
			[
				'label' => esc_html__( 'Image', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$widget->add_responsive_control(
			"{$prefix}_image_aspect_ratio",
			[
				'label'          => esc_html__( 'Aspect Ratio', 'elementor-dynamic-toolkit' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '16-9',
				'tablet_default' => '16-9',
				'mobile_default' => '4-3',
				'options'        => [
					'16-9'  => '16:9',
					'4-3'   => '4:3',
					'1-1'   => '1:1',
					'3-2'   => '3:2',
					'21-9'  => '21:9',
					'2-3'   => '2:3 (Portrait)',
					'3-4'   => '3:4 (Portrait)',
					'auto'  => esc_html__( 'Auto / Inherit', 'elementor-dynamic-toolkit' ),
				],
				'selectors'      => [
					"{{WRAPPER}} {$wrapper_selector}" => 'aspect-ratio: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'16-9' => '16/9',
					'4-3'  => '4/3',
					'1-1'  => '1/1',
					'3-2'  => '3/2',
					'21-9' => '21/9',
					'2-3'  => '2/3',
					'3-4'  => '3/4',
					'auto' => 'auto',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_image_object_fit",
			[
				'label'     => esc_html__( 'Object Fit', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => [
					'cover'   => esc_html__( 'Cover', 'elementor-dynamic-toolkit' ),
					'contain' => esc_html__( 'Contain', 'elementor-dynamic-toolkit' ),
					'fill'    => esc_html__( 'Fill', 'elementor-dynamic-toolkit' ),
					'none'    => esc_html__( 'None', 'elementor-dynamic-toolkit' ),
				],
				'selectors' => [
					"{{WRAPPER}} {$img_selector}" => 'object-fit: {{VALUE}};',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_image_object_position",
			[
				'label'     => esc_html__( 'Object Position', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center center',
				'options'   => [
					'center center' => esc_html__( 'Center', 'elementor-dynamic-toolkit' ),
					'top center'    => esc_html__( 'Top Center', 'elementor-dynamic-toolkit' ),
					'bottom center' => esc_html__( 'Bottom Center', 'elementor-dynamic-toolkit' ),
					'left center'   => esc_html__( 'Left', 'elementor-dynamic-toolkit' ),
					'right center'  => esc_html__( 'Right', 'elementor-dynamic-toolkit' ),
				],
				'selectors' => [
					"{{WRAPPER}} {$img_selector}" => 'object-position: {{VALUE}};',
				],
			]
		);

		$widget->add_responsive_control(
			"{$prefix}_image_border_radius",
			[
				'label'      => esc_html__( 'Border Radius', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					"{{WRAPPER}} {$img_selector}" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_image_opacity",
			[
				'label'     => esc_html__( 'Opacity', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					],
				],
				'selectors' => [
					"{{WRAPPER}} {$img_selector}" => 'opacity: {{SIZE}};',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_image_hover_opacity",
			[
				'label'     => esc_html__( 'Opacity on Hover', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					],
				],
				'selectors' => [
					"{{WRAPPER}} .edt-card:hover {$img_selector}" => 'opacity: {{SIZE}};',
				],
			]
		);

		$widget->end_controls_section();
	}

	// -------------------------------------------------------------------------
	// Button style section
	// -------------------------------------------------------------------------

	/**
	 * Adds a full button style section (typography, colors, padding, border, radius + hover).
	 */
	protected function add_button_style_section( Widget_Base $widget, string $prefix, string $selector ): void {
		$widget->start_controls_section(
			"style_{$prefix}_btn",
			[
				'label' => esc_html__( 'Button / CTA', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$widget->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => "{$prefix}_btn_typography",
				'selector' => "{{WRAPPER}} {$selector}",
			]
		);

		$widget->start_controls_tabs( "{$prefix}_btn_state_tabs" );

		$widget->start_controls_tab(
			"{$prefix}_btn_normal_tab",
			[ 'label' => esc_html__( 'Normal', 'elementor-dynamic-toolkit' ) ]
		);

		$widget->add_control(
			"{$prefix}_btn_color",
			[
				'label'     => esc_html__( 'Text Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					"{{WRAPPER}} {$selector}" => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_btn_bg_color",
			[
				'label'     => esc_html__( 'Background Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					"{{WRAPPER}} {$selector}" => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab(
			"{$prefix}_btn_hover_tab",
			[ 'label' => esc_html__( 'Hover', 'elementor-dynamic-toolkit' ) ]
		);

		$widget->add_control(
			"{$prefix}_btn_hover_color",
			[
				'label'     => esc_html__( 'Text Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					"{{WRAPPER}} {$selector}:hover" => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_btn_hover_bg_color",
			[
				'label'     => esc_html__( 'Background Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					"{{WRAPPER}} {$selector}:hover" => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_btn_hover_border_color",
			[
				'label'     => esc_html__( 'Border Color', 'elementor-dynamic-toolkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					"{{WRAPPER}} {$selector}:hover" => 'border-color: {{VALUE}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_control(
			"{$prefix}_btn_separator",
			[ 'type' => Controls_Manager::DIVIDER ]
		);

		$widget->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => "{$prefix}_btn_border",
				'selector' => "{{WRAPPER}} {$selector}",
			]
		);

		$widget->add_responsive_control(
			"{$prefix}_btn_border_radius",
			[
				'label'      => esc_html__( 'Border Radius', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					"{{WRAPPER}} {$selector}" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			"{$prefix}_btn_radius_important",
			[
				'label'       => esc_html__( 'Force Radius (!important)', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::SWITCHER,
				'selectors'   => [
					"{{WRAPPER}} {$selector}" => 'border-radius: {{' . $prefix . '_btn_border_radius.TOP}}{{' . $prefix . '_btn_border_radius.UNIT}} {{' . $prefix . '_btn_border_radius.RIGHT}}{{' . $prefix . '_btn_border_radius.UNIT}} {{' . $prefix . '_btn_border_radius.BOTTOM}}{{' . $prefix . '_btn_border_radius.UNIT}} {{' . $prefix . '_btn_border_radius.LEFT}}{{' . $prefix . '_btn_border_radius.UNIT}} !important;',
				],
			]
		);

		$widget->add_responsive_control(
			"{$prefix}_btn_padding",
			[
				'label'      => esc_html__( 'Padding', 'elementor-dynamic-toolkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					"{{WRAPPER}} {$selector}" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => "{$prefix}_btn_box_shadow",
				'selector' => "{{WRAPPER}} {$selector}",
			]
		);

		$widget->end_controls_section();
	}
}
