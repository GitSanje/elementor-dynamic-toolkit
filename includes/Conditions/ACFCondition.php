<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class ACFCondition implements ConditionInterface {
	public function __construct( private readonly string $field, private readonly string $expected ) {}

	public function evaluate( array $context = [] ): bool {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$post_id = absint( $context['post_id'] ?? get_the_ID() );
		$value   = get_field( sanitize_key( $this->field ), $post_id );
		return is_scalar( $value ) && hash_equals( $this->expected, (string) $value );
	}
}