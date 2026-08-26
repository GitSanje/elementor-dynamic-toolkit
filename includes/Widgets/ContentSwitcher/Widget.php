<?php
/**
 * Content switcher widget.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Widgets\ContentSwitcher;

defined( 'ABSPATH' ) || exit;

final class Widget extends \Elementor\Widget_Base {

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
		return [ 'general' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'elementor-dynamic-toolkit' ),
			]
		);

		$this->add_control(
			'content_a',
			[
				'label' => esc_html__( 'Content A', 'elementor-dynamic-toolkit' ),
				'type'  => \Elementor\Controls_Manager::TEXTAREA,
			]
		);

		$this->add_control(
			'content_b',
			[
				'label' => esc_html__( 'Content B', 'elementor-dynamic-toolkit' ),
				'type'  => \Elementor\Controls_Manager::TEXTAREA,
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$content = ! empty( $settings['content_a'] ) ? $settings['content_a'] : $settings['content_b'] ?? '';
		echo '<div class="edt-content-switcher">' . wp_kses_post( $content ) . '</div>';
	}
}
