<?php
/**
 * Date & Time Condition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class DateCondition implements ConditionInterface {

	public function get_id(): string {
		return 'date_time';
	}

	public function get_title(): string {
		return esc_html__( 'Date & Time Range', 'elementor-dynamic-toolkit' );
	}

	public function get_group(): string {
		return esc_html__( 'Date & Time', 'elementor-dynamic-toolkit' );
	}

	public function get_operators(): array {
		return [
			'after'   => esc_html__( 'After', 'elementor-dynamic-toolkit' ),
			'before'  => esc_html__( 'Before', 'elementor-dynamic-toolkit' ),
			'between' => esc_html__( 'Between', 'elementor-dynamic-toolkit' ),
		];
	}

	public function evaluate( array $rule = [], array $context = [] ): bool {
		$now      = current_time( 'timestamp' );
		$operator = $rule['operator'] ?? 'after';

		if ( 'between' === $operator ) {
			$start = ! empty( $rule['start_date'] ) ? strtotime( (string) $rule['start_date'] ) : 0;
			$end   = ! empty( $rule['end_date'] ) ? strtotime( (string) $rule['end_date'] ) : PHP_INT_MAX;
			return $now >= $start && $now <= $end;
		}

		$target = ! empty( $rule['value'] ) ? strtotime( (string) $rule['value'] ) : 0;
		if ( 'before' === $operator ) {
			return $now < $target;
		}

		return $now > $target;
	}
}