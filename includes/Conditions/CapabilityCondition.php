<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class CapabilityCondition implements ConditionInterface {
	public function __construct( private readonly string $capability ) {}

	public function evaluate( array $context = [] ): bool {
		return current_user_can( $this->capability );
	}
}