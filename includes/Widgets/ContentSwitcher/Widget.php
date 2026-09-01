<?php
/**
 * Content Switcher Widget.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\ContentSwitcher;

use EDT\Controls\VisibilityControl;
use EDT\Rendering\RenderContext;
use EDT\Rendering\WidgetRenderer;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

final class Widget extends \Elementor\Widget_Base {

	private ?WidgetRenderer $renderer = null;

	public function get_name(): string {
		return 'edt_content_switcher';
	}

	public function get_title(): string {
		return esc_html__( 'Content Switcher', 'elementor-dynamic-toolkit' );
	}

	public function get_icon(): string {
		return 'eicon-tabs';
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
			'content_section',
			[
				'label' => esc_html__( 'Switcher Content', 'elementor-dynamic-toolkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'label_a',
			[
				'label'   => esc_html__( 'Option 1 Label', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Monthly', 'elementor-dynamic-toolkit' ),
			]
		);

		$this->add_control(
			'content_a',
			[
				'label'   => esc_html__( 'Option 1 Content (HTML / Text)', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => '<p>' . esc_html__( 'Standard Monthly Billing Details...', 'elementor-dynamic-toolkit' ) . '</p>',
			]
		);

		$this->add_control(
			'label_b',
			[
				'label'   => esc_html__( 'Option 2 Label', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Annual (Save 20%)', 'elementor-dynamic-toolkit' ),
			]
		);

		$this->add_control(
			'content_b',
			[
				'label'   => esc_html__( 'Option 2 Content (HTML / Text)', 'elementor-dynamic-toolkit' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => '<p>' . esc_html__( 'Discounted Annual Billing Details...', 'elementor-dynamic-toolkit' ) . '</p>',
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
			'widgets/content-switcher/wrapper',
			[
				'context'  => $context,
				'renderer' => $this->get_renderer(),
			]
		);
	}
}
