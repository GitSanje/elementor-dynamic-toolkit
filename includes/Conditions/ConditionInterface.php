<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

interface ConditionInterface {
	public function evaluate( array $context = [] ): bool;
}