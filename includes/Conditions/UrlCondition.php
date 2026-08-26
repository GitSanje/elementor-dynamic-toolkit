<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class UrlCondition implements ConditionInterface {
	public function __construct( private readonly string $parameter, private readonly string $expected ) {}

	public function evaluate( array $context = [] ): bool {
		$value = isset( $_GET[ $this->parameter ] ) ? sanitize_text_field( wp_unslash( $_GET[ $this->parameter ] ) ) : '';
		return hash_equals( $this->expected, $value );
	}
}