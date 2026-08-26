<?php
/**
 * Reusable dynamic-field selector for provider-driven data sources.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

defined( 'ABSPATH' ) || exit;

final class DynamicFieldControl {

	public static function config( string $label = 'Field', array $options = [] ): array {
		return [
			'label'   => esc_html__( $label, 'elementor-dynamic-toolkit' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => $options,
		];
	}
}
