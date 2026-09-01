<?php
/**
 * User Role Condition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class RoleCondition implements ConditionInterface {

	public function get_id(): string {
		return 'user_role';
	}

	public function get_title(): string {
		return esc_html__( 'User Role', 'elementor-dynamic-toolkit' );
	}

	public function get_group(): string {
		return esc_html__( 'User', 'elementor-dynamic-toolkit' );
	}

	public function get_operators(): array {
		return [
			'is'     => esc_html__( 'Is', 'elementor-dynamic-toolkit' ),
			'is_not' => esc_html__( 'Is Not', 'elementor-dynamic-toolkit' ),
			'in'     => esc_html__( 'In', 'elementor-dynamic-toolkit' ),
		];
	}

	public function evaluate( array $rule = [], array $context = [] ): bool {
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			return false;
		}

		$user  = wp_get_current_user();
		$roles = (array) ( $user->roles ?? [] );
		$target_roles = (array) ( $rule['value'] ?? [] );
		$operator     = $rule['operator'] ?? 'is';

		$intersection = array_intersect( $roles, $target_roles );
		$has_role     = ! empty( $intersection );

		return 'is_not' === $operator ? ! $has_role : $has_role;
	}
}