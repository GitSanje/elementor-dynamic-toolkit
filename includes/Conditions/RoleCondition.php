<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class RoleCondition implements ConditionInterface {
	public function __construct( private readonly string $role ) {}

	public function evaluate( array $context = [] ): bool {
		$user = wp_get_current_user();
		return in_array( $this->role, (array) $user->roles, true );
	}
}