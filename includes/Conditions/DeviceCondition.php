<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class DeviceCondition implements ConditionInterface {
	public function __construct( private readonly string $device ) {}

	public function evaluate( array $context = [] ): bool {
		$current = sanitize_key( (string) ( $context['device'] ?? '' ) );
		return $current === sanitize_key( $this->device );
	}
}