<?php
/**
 * Shared base for toolkit Dynamic Tags.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

defined( 'ABSPATH' ) || exit;

abstract class BaseTag extends \Elementor\Core\DynamicTags\Tag {

	public function get_group(): string {
		return 'edt';
	}

	public function get_categories(): array {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function get_post_id(): int {
		return absint( get_the_ID() );
	}

	protected function output_value( $value ): void {
		if ( is_scalar( $value ) ) {
			echo esc_html( (string) $value );
		}
	}
}