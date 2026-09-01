<?php
/**
 * AST Node / Composite for nested condition rules.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class RuleGroup {

	/**
	 * @param string $relation 'AND' or 'OR' or 'NOT'
	 * @param array<int, array|RuleGroup|ConditionInterface> $rules
	 */
	public function __construct(
		private string $relation = 'AND',
		private array $rules = []
	) {
		$this->relation = in_array( strtoupper( $relation ), [ 'AND', 'OR', 'NOT' ], true ) ? strtoupper( $relation ) : 'AND';
	}

	public function add_rule( array|RuleGroup|ConditionInterface $rule ): self {
		$this->rules[] = $rule;
		return $this;
	}

	public function evaluate( ConditionManager $manager, array $context = [] ): bool {
		if ( empty( $this->rules ) ) {
			return true;
		}

		$results = [];

		foreach ( $this->rules as $rule ) {
			if ( $rule instanceof self ) {
				$results[] = $rule->evaluate( $manager, $context );
			} elseif ( $rule instanceof ConditionInterface ) {
				$results[] = $rule->evaluate( [], $context );
			} elseif ( is_array( $rule ) ) {
				if ( isset( $rule['relation'] ) && isset( $rule['rules'] ) ) {
					$nested_group = new self( (string) $rule['relation'], (array) $rule['rules'] );
					$results[]    = $nested_group->evaluate( $manager, $context );
				} elseif ( isset( $rule['condition'] ) || isset( $rule['type'] ) ) {
					$key       = (string) ( $rule['condition'] ?? $rule['type'] ?? '' );
					$condition = $manager->get( $key );
					$results[] = $condition ? $condition->evaluate( $rule, $context ) : false;
				} else {
					$results[] = false;
				}
			}
		}

		if ( 'NOT' === $this->relation ) {
			return ! ( $results[0] ?? false );
		}

		if ( 'OR' === $this->relation ) {
			return in_array( true, $results, true );
		}

		return ! in_array( false, $results, true );
	}
}
