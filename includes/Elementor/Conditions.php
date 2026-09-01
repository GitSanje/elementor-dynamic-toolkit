<?php
/**
 * Elementor conditions registration boundary.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

use EDT\Conditions\ConditionManager;
use EDT\Conditions\RuleGroup;
use EDT\Support\Helpers;

defined( 'ABSPATH' ) || exit;

final class Conditions {

	public function register(): void {
		add_filter(
			'elementor/widget/render_content',
			[ $this, 'filter_element_content' ],
			10,
			2
		);
	}

	public function filter_element_content( string $content, mixed $element ): string {
		if ( ! is_object( $element ) || ! method_exists( $element, 'get_settings_for_display' ) ) {
			return $content;
		}

		$settings = $element->get_settings_for_display();
		if ( ( $settings['edt_visibility_enabled'] ?? '' ) !== 'yes' ) {
			return $content;
		}

		$action = $settings['edt_visibility_action'] ?? 'show';
		$rules  = [];

		// User Status Rule
		$login_status = sanitize_key( (string) ( $settings['edt_visibility_login'] ?? '' ) );
		if ( ! empty( $login_status ) ) {
			$rules[] = [
				'condition' => 'user_status',
				'operator'  => 'is',
				'value'     => $login_status,
			];
		}

		// Role Rule
		$roles = $settings['edt_visibility_role'] ?? [];
		if ( ! empty( $roles ) && is_array( $roles ) ) {
			$rules[] = [
				'condition' => 'user_role',
				'operator'  => 'is',
				'value'     => $roles,
			];
		}

		// Device Rule
		$device = sanitize_key( (string) ( $settings['edt_visibility_device'] ?? '' ) );
		if ( ! empty( $device ) ) {
			$rules[] = [
				'condition' => 'device',
				'operator'  => 'is',
				'value'     => $device,
			];
		}

		// Visual Rule Builder Rules
		if ( ! empty( $settings['edt_visibility_rules'] ) && is_array( $settings['edt_visibility_rules'] ) ) {
			$rules = array_merge( $rules, $settings['edt_visibility_rules'] );
		}

		if ( empty( $rules ) ) {
			return $content;
		}

		$context  = Helpers::maybe_get_editor_context();
		$manager  = new ConditionManager();
		$matches  = $manager->evaluate( $rules, $context, 'AND' );

		$should_render = ( 'show' === $action ) ? $matches : ! $matches;

		return $should_render ? $content : '';
	}
}