<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class UserCondition implements ConditionInterface {
	public function __construct( private readonly bool $logged_in = true ) {}

	public function evaluate( array $context = [] ): bool {
		return is_user_logged_in() === $this->logged_in;
	}
}