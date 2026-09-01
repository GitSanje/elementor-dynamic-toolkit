<?php
/**
 * URL Parameter & Query String Condition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class UrlCondition implements ConditionInterface {

	public function get_id(): string {
		return 'url_parameter';
	}

	public function get_title(): string {
		return esc_html__( 'URL Parameter / Query String', 'elementor-dynamic-toolkit' );
	}

	public function get_group(): string {
		return esc_html__( 'Request', 'elementor-dynamic-toolkit' );
	}

	public function get_operators(): array {
		return [
			'equals'   => esc_html__( 'Equals', 'elementor-dynamic-toolkit' ),
			'contains' => esc_html__( 'Contains', 'elementor-dynamic-toolkit' ),
			'exists'   => esc_html__( 'Exists', 'elementor-dynamic-toolkit' ),
		];
	}

	public function evaluate( array $rule = [], array $context = [] ): bool {
		$param_name = sanitize_key( (string) ( $rule['param'] ?? '' ) );
		if ( '' === $param_name ) {
			return true;
		}

		$param_value = isset( $_GET[ $param_name ] ) ? sanitize_text_field( wp_unslash( (string) $_GET[ $param_name ] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$operator    = $rule['operator'] ?? 'equals';
		$target_val  = (string) ( $rule['value'] ?? '' );

		if ( 'exists' === $operator ) {
			return null !== $param_value;
		}

		if ( null === $param_value ) {
			return false;
		}

		if ( 'contains' === $operator ) {
			return str_contains( $param_value, $target_val );
		}

		return $param_value === $target_val;
	}
}