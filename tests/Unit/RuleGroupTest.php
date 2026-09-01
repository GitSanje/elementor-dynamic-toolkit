<?php

namespace EDT\Tests\Unit;

use EDT\Conditions\ConditionManager;
use EDT\Conditions\RuleGroup;
use EDT\Conditions\UserCondition;
use PHPUnit\Framework\TestCase;

final class RuleGroupTest extends TestCase {

	public function test_evaluates_and_rules(): void {
		$manager = new ConditionManager();
		$group   = new RuleGroup( 'AND' );

		$group->add_rule( new UserCondition( true ) );

		// Under mock, is_user_logged_in is not defined -> false
		$this->assertFalse( $group->evaluate( $manager ) );

		$group2 = new RuleGroup( 'AND' );
		$group2->add_rule( new UserCondition( false ) );
		$this->assertTrue( $group2->evaluate( $manager ) );
	}

	public function test_evaluates_nested_or_rules(): void {
		$manager = new ConditionManager();
		$root    = new RuleGroup( 'OR' );

		$root->add_rule( new UserCondition( true ) );
		$root->add_rule( new UserCondition( false ) );

		$this->assertTrue( $root->evaluate( $manager ) );
	}
}
