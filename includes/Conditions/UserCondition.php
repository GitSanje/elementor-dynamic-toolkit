<?php
/**
 * User Login Status Condition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class UserCondition implements ConditionInterface {

	public function __construct( private readonly ?bool $default_logged_in = null ) {}

	public function get_id(): string {
		return 'user_status';
	}

	public function get_title(): string {
		return esc_html__( 'User Status (Logged In / Guest)', 'elementor-dynamic-toolkit' );
	}

	public function get_group(): string {
		return esc_html__( 'User', 'elementor-dynamic-toolkit' );
	}

	public function get_operators(): array {
		return [
			'is'     => esc_html__( 'Is', 'elementor-dynamic-toolkit' ),
			'is_not' => esc_html__( 'Is Not', 'elementor-dynamic-toolkit' ),
		];
	}

	public function evaluate( array $rule = [], array $context = [] ): bool {
		if ( null !== $this->default_logged_in ) {
			$is_logged_in = function_exists( 'is_user_logged_in' ) && is_user_logged_in();
			return $is_logged_in === $this->default_logged_in;
		}

		$operator     = $rule['operator'] ?? 'is';
		$target_state = $rule['value'] ?? 'logged_in';
		$is_logged_in = function_exists( 'is_user_logged_in' ) && is_user_logged_in();

		$matches = ( 'logged_in' === $target_state && $is_logged_in ) || ( 'guest' === $target_state && ! $is_logged_in );

		return 'is_not' === $operator ? ! $matches : $matches;
	}
}