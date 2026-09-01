<?php
/**
 * Device Viewport Condition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class DeviceCondition implements ConditionInterface {

	public function get_id(): string {
		return 'device';
	}

	public function get_title(): string {
		return esc_html__( 'Client Device', 'elementor-dynamic-toolkit' );
	}

	public function get_group(): string {
		return esc_html__( 'Environment', 'elementor-dynamic-toolkit' );
	}

	public function get_operators(): array {
		return [
			'is'     => esc_html__( 'Is', 'elementor-dynamic-toolkit' ),
			'is_not' => esc_html__( 'Is Not', 'elementor-dynamic-toolkit' ),
		];
	}

	public function evaluate( array $rule = [], array $context = [] ): bool {
		$device   = $context['device'] ?? ( function_exists( 'wp_is_mobile' ) && wp_is_mobile() ? 'mobile' : 'desktop' );
		$target   = $rule['value'] ?? 'desktop';
		$operator = $rule['operator'] ?? 'is';

		$match = $device === $target;
		return 'is_not' === $operator ? ! $match : $match;
	}
}