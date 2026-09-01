<?php
/**
 * Condition Interface.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

interface ConditionInterface {

	public function get_id(): string;

	public function get_title(): string;

	public function get_group(): string;

	/**
	 * @return array<string, string>
	 */
	public function get_operators(): array;

	/**
	 * @param array<string, mixed> $rule
	 * @param array<string, mixed> $context
	 */
	public function evaluate( array $rule = [], array $context = [] ): bool;
}