<?php
/**
 * Condition Registry and Rule Engine Manager.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class ConditionManager {

	/**
	 * @var array<string, ConditionInterface>
	 */
	private array $conditions = [];

	public function __construct() {
		$this->register( 'user_status', new UserCondition() );
		$this->register( 'user_role', new RoleCondition() );
		$this->register( 'user_capability', new CapabilityCondition() );
		$this->register( 'post_type', new PostCondition() );
		$this->register( 'date_time', new DateCondition() );
		$this->register( 'device', new DeviceCondition() );
		$this->register( 'url_parameter', new UrlCondition() );

		if ( function_exists( 'get_field' ) ) {
			$this->register( 'acf_field', new ACFCondition() );
		}

		if ( function_exists( 'WC' ) ) {
			$this->register( 'woocommerce', new WooCondition() );
		}
	}

	public function register( string $key, ConditionInterface $condition ): void {
		$this->conditions[ sanitize_key( $key ) ] = $condition;
	}

	public function get( string $key ): ?ConditionInterface {
		$conditions = $this->get_all();
		return $conditions[ sanitize_key( $key ) ] ?? null;
	}

	/**
	 * @return array<string, ConditionInterface>
	 */
	public function get_all(): array {
		$conditions = apply_filters( 'edt/conditions', $this->conditions );
		return is_array( $conditions ) ? $conditions : [];
	}

	public function evaluate( array $rules, array $context = [], string $operator = 'AND' ): bool {
		if ( empty( $rules ) ) {
			return true;
		}

		$group = new RuleGroup( $operator, $rules );
		return $group->evaluate( $this, $context );
	}
}