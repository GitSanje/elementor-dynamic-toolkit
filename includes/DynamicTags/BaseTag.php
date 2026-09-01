<?php
/**
 * Shared base for toolkit Dynamic Tags with formatting and UX options.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

use EDT\Providers\ProviderManager;

defined( 'ABSPATH' ) || exit;

abstract class BaseTag extends \Elementor\Core\DynamicTags\Tag {

	public function get_group(): string {
		return 'edt';
	}

	public function get_categories(): array {
		return [
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
		];
	}

	protected function get_post_id(): int {
		$id = get_the_ID();
		return $id ? absint( $id ) : 0;
	}

	protected function get_provider_manager(): ProviderManager {
		return new ProviderManager();
	}

	protected function register_advanced_controls(): void {
		$this->add_control(
			'before',
			[
				'label'       => esc_html__( 'Before', 'elementor-dynamic-toolkit' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'after',
			[
				'label'       => esc_html__( 'After', 'elementor-dynamic-toolkit' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'fallback',
			[
				'label'       => esc_html__( 'Fallback', 'elementor-dynamic-toolkit' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
			]
		);
	}

	protected function output_value( mixed $value ): void {
		$settings = $this->get_settings();
		$before   = (string) ( $settings['before'] ?? '' );
		$after    = (string) ( $settings['after'] ?? '' );
		$fallback = (string) ( $settings['fallback'] ?? '' );

		$string_value = '';
		if ( is_scalar( $value ) && '' !== (string) $value ) {
			$string_value = (string) $value;
		} elseif ( is_array( $value ) && ! empty( $value ) ) {
			$string_value = implode( ', ', array_map( 'strval', $value ) );
		}

		if ( '' === $string_value ) {
			if ( '' !== $fallback ) {
				echo esc_html( $before . $fallback . $after );
			}
			return;
		}

		echo esc_html( $before . $string_value . $after );
	}
}