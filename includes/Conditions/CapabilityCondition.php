<?php
/**
 * User Capability Condition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class CapabilityCondition implements ConditionInterface {

	public function get_id(): string {
		return 'user_capability';
	}

	public function get_title(): string {
		return esc_html__( 'User Capability', 'elementor-dynamic-toolkit' );
	}

	public function get_group(): string {
		return esc_html__( 'User', 'elementor-dynamic-toolkit' );
	}

	public function get_operators(): array {
		return [
			'can'    => esc_html__( 'Can', 'elementor-dynamic-toolkit' ),
			'cannot' => esc_html__( 'Cannot', 'elementor-dynamic-toolkit' ),
		];
	}

	public function evaluate( array $rule = [], array $context = [] ): bool {
		$cap = sanitize_key( (string) ( $rule['value'] ?? 'read' ) );
		$operator = $rule['operator'] ?? 'can';

		$can = function_exists( 'current_user_can' ) && current_user_can( $cap );
		return 'cannot' === $operator ? ! $can : $can;
	}
}