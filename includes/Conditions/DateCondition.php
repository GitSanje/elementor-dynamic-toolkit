<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class DateCondition implements ConditionInterface {
	public function __construct( private readonly string $operator, private readonly string $date ) {}

	public function evaluate( array $context = [] ): bool {
		$target = strtotime( $this->date );
		$now    = current_time( 'timestamp' );

		if ( false === $target ) {
			return false;
		}

		return match ( $this->operator ) {
			'<'  => $now < $target,
			'<=' => $now <= $target,
			'>'  => $now > $target,
			'>=' => $now >= $target,
			default => $now === $target,
		};
	}
}