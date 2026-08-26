<?php
/**
 * Shared condition visibility control settings.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

defined( 'ABSPATH' ) || exit;

final class VisibilityControl {

	public static function config( string $label = 'Show if condition' ): array {
		return [
			'label' => esc_html__( $label, 'elementor-dynamic-toolkit' ),
			'type'  => \Elementor\Controls_Manager::TEXTAREA,
		];
	}
}
