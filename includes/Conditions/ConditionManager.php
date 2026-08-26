<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class ConditionManager {

	/**
	 * @var array<string, ConditionInterface>
	 */
	private array $conditions = [];

	public function register( string $key, ConditionInterface $condition ): void {
		$this->conditions[ sanitize_key( $key ) ] = $condition;
	}

	public function get( string $key ): ?ConditionInterface {
		$key = sanitize_key( $key );
		return $this->conditions[ $key ] ?? null;
	}

	public function evaluate( array $rules, array $context = [], string $operator = 'AND' ): bool {
		$results = [];

		foreach ( $rules as $rule ) {
			if ( $rule instanceof ConditionInterface ) {
				$results[] = $rule->evaluate( $context );
				continue;
			}

			if ( is_array( $rule ) && isset( $rule['condition'] ) ) {
				$condition = $this->resolve_condition( $rule['condition'] );
				$results[] = $condition ? $condition->evaluate( $context ) : false;
				continue;
			}

			$results[] = false;
		}

		$normalized_operator = strtoupper( $operator );

		if ( 'NOT' === $normalized_operator ) {
			return ! ( $results[0] ?? false );
		}

		if ( 'OR' === $normalized_operator ) {
			return in_array( true, $results, true );
		}

		return ! in_array( false, $results, true );
	}

	private function resolve_condition( mixed $condition ): ?ConditionInterface {
		if ( $condition instanceof ConditionInterface ) {
			return $condition;
		}

		if ( is_callable( $condition ) ) {
			$instance = $condition();
			return $instance instanceof ConditionInterface ? $instance : null;
		}

		if ( is_string( $condition ) ) {
			return $this->get( $condition );
		}

		return null;
	}
}