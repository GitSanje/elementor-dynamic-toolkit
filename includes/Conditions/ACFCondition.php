<?php
/**
 * ACF Field Value Condition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class ACFCondition implements ConditionInterface {

	public function get_id(): string {
		return 'acf_field';
	}

	public function get_title(): string {
		return esc_html__( 'ACF Field Value', 'elementor-dynamic-toolkit' );
	}

	public function get_group(): string {
		return esc_html__( 'ACF', 'elementor-dynamic-toolkit' );
	}

	public function get_operators(): array {
		return [
			'equals'     => esc_html__( 'Equals', 'elementor-dynamic-toolkit' ),
			'not_equals' => esc_html__( 'Does Not Equal', 'elementor-dynamic-toolkit' ),
			'contains'   => esc_html__( 'Contains', 'elementor-dynamic-toolkit' ),
			'empty'      => esc_html__( 'Is Empty', 'elementor-dynamic-toolkit' ),
			'not_empty'  => esc_html__( 'Is Not Empty', 'elementor-dynamic-toolkit' ),
		];
	}

	public function evaluate( array $rule = [], array $context = [] ): bool {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$field_name = sanitize_key( (string) ( $rule['field'] ?? '' ) );
		if ( '' === $field_name ) {
			return true;
		}

		$post_id   = $context['post_id'] ?? get_the_ID();
		$val       = get_field( $field_name, $post_id ?: false );
		$operator  = $rule['operator'] ?? 'not_empty';
		$target    = $rule['value'] ?? '';

		return match ( $operator ) {
			'empty'      => empty( $val ),
			'not_empty'  => ! empty( $val ),
			'equals'     => (string) $val === (string) $target,
			'not_equals' => (string) $val !== (string) $target,
			'contains'   => is_scalar( $val ) && str_contains( (string) $val, (string) $target ),
			default      => ! empty( $val ),
		};
	}
}