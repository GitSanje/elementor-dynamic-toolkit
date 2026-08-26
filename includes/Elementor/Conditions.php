<?php
/**
 * Elementor conditions registration boundary.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

defined( 'ABSPATH' ) || exit;

final class Conditions {

	public function register(): void {
		add_filter(
			'elementor/widget/render_content',
			static function ( $content, $widget ): string {
				if ( ! $widget instanceof \Elementor\Widget_Base ) {
					return $content;
				}

				$settings = $widget->get_settings_for_display();
				$rules = [];
				if ( 'yes' === ( $settings['edt_visibility_enabled'] ?? '' ) ) {
					$login_status = sanitize_key( (string) ( $settings['edt_visibility_login'] ?? '' ) );
					if ( 'logged' === $login_status ) {
						$rules[] = new \EDT\Conditions\UserCondition( true );
					} elseif ( 'guest' === $login_status ) {
						$rules[] = new \EDT\Conditions\UserCondition( false );
					}
				}

				if ( ! empty( $settings['edt_visibility'] ) && is_array( $settings['edt_visibility'] ) ) {
					$rules = $settings['edt_visibility'];
				}

				if ( empty( $rules ) ) {
					return $content;
				}

				$manager = new \EDT\Conditions\ConditionManager();
				$context = [
					'post_id' => absint( get_the_ID() ),
					'device'  => wp_is_mobile() ? 'mobile' : 'desktop',
				];

				return $manager->evaluate( $rules, $context ) ? $content : '';
			},
			10,
			2
		);
	}
}