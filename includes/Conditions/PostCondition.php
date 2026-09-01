<?php
/**
 * Post & Archive Condition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class PostCondition implements ConditionInterface {

	public function get_id(): string {
		return 'post_type';
	}

	public function get_title(): string {
		return esc_html__( 'Post Type', 'elementor-dynamic-toolkit' );
	}

	public function get_group(): string {
		return esc_html__( 'Post', 'elementor-dynamic-toolkit' );
	}

	public function get_operators(): array {
		return [
			'is'     => esc_html__( 'Is', 'elementor-dynamic-toolkit' ),
			'is_not' => esc_html__( 'Is Not', 'elementor-dynamic-toolkit' ),
		];
	}

	public function evaluate( array $rule = [], array $context = [] ): bool {
		$post_id   = (int) ( $context['post_id'] ?? get_the_ID() );
		$post_type = $post_id ? get_post_type( $post_id ) : ( get_query_var( 'post_type' ) ?: 'post' );

		$target_type = (string) ( $rule['value'] ?? 'post' );
		$operator    = (string) ( $rule['operator'] ?? 'is' );

		$match = $post_type === $target_type;
		return 'is_not' === $operator ? ! $match : $match;
	}
}