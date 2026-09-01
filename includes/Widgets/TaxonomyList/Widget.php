<?php
/**
 * Taxonomy List Widget.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\TaxonomyList;

use EDT\Controls\TaxonomyControl;
use EDT\Controls\VisibilityControl;
use EDT\Rendering\RenderContext;
use EDT\Rendering\WidgetRenderer;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class Widget extends \Elementor\Widget_Base {

	private ?WidgetRenderer $renderer = null;

	public function get_name(): string {
		return 'edt_taxonomy_list';
	}

	public function get_title(): string {
		return esc_html__( 'Taxonomy List', 'elementor-dynamic-toolkit' );
	}

	public function get_icon(): string {
		return 'eicon-tags';
	}

	public function get_categories(): array {
		return [ \EDT\Elementor\Categories::SLUG ];
	}

	public function get_renderer(): WidgetRenderer {
		$this->renderer ??= new WidgetRenderer();
		return $this->renderer;
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'taxonomy_section',
			[
				'label' => esc_html__( 'Taxonomy Settings', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label'   => esc_html__( 'Taxonomy', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => TaxonomyControl::options(),
				'default' => 'category',
			]
		);

		$this->add_control(
			'layout_style',
			[
				'label'   => esc_html__( 'Display Style', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'list'   => esc_html__( 'List View', 'elementor-dynamic-toolkit' ),
					'inline' => esc_html__( 'Inline Cloud / Tags', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'list',
			]
		);

		$this->add_control(
			'show_count',
			[
				'label'        => esc_html__( 'Show Post Count Badge', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'        => esc_html__( 'Hide Empty Terms', 'elementor-dynamic-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		VisibilityControl::add_controls( $this );
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$settings['widget_id'] = $this->get_id();
		$context  = new RenderContext( $settings );

		$this->get_renderer()->render(
			'widgets/taxonomy-list/wrapper',
			[
				'context'  => $context,
				'renderer' => $this->get_renderer(),
			]
		);
	}
}
