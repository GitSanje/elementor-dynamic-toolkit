<?php
/**
 * Repeater-based element ordering trait for EDT widgets.
 *
 * Allows users to drag-and-reorder widget elements (image, title, meta, excerpt, button)
 * in the Elementor editor panel, using a Repeater control.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\Traits;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

trait ElementOrderTrait {

	/**
	 * Registers the element-order Repeater control.
	 *
	 * @param Widget_Base $widget   The widget instance.
	 * @param array       $elements Ordered list of element slugs and labels:
	 *                              [ 'image' => 'Featured Image', 'title' => 'Title', ... ]
	 */
	protected function register_order_controls( Widget_Base $widget, array $elements ): void {
		$widget->start_controls_section(
			'section_element_order',
			[
				'label' => esc_html__( 'Element Order & Visibility', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'element_order_notice',
			[
				'type'            => Controls_Manager::NOTICE,
				'notice_type'     => 'info',
				'content'         => esc_html__( 'Drag rows to reorder how elements appear in each card. Toggle visibility with the eye icon.', 'elementor-dynamic-toolkit' ),
				'dismissible'     => false,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'element_key',
			[
				'label'   => esc_html__( 'Element', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $elements,
			]
		);

		$repeater->add_control(
			'element_visible',
			[
				'label'        => esc_html__( 'Visible', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		// Build the default repeater value from the passed element map.
		$default_items = [];
		foreach ( $elements as $slug => $label ) {
			$default_items[] = [
				'element_key'     => $slug,
				'element_visible' => 'yes',
			];
		}

		$widget->add_control(
			'element_order',
			[
				'label'       => esc_html__( 'Elements', 'elementor-dynamic-toolkit' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $default_items,
				'title_field' => '{{{ element_key }}}',
			]
		);

		$widget->end_controls_section();
	}

	/**
	 * Returns visible element keys in user-defined repeater order.
	 *
	 * @param array $settings Widget settings (get_settings_for_display()).
	 * @param array $fallback Default element order if repeater is empty.
	 *
	 * @return string[] Ordered, filtered list of element keys.
	 */
	protected function get_ordered_elements( array $settings, array $fallback = [] ): array {
		$repeater_items = $settings['element_order'] ?? [];

		if ( empty( $repeater_items ) ) {
			return array_keys( $fallback );
		}

		$ordered = [];
		foreach ( $repeater_items as $item ) {
			if ( ! empty( $item['element_key'] ) && ( $item['element_visible'] ?? 'yes' ) === 'yes' ) {
				$ordered[] = (string) $item['element_key'];
			}
		}

		return $ordered;
	}
}
