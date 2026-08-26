<?php
/**
 * Reusable meta field control definition.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

defined( 'ABSPATH' ) || exit;

final class MetaFieldControl {

	public static function config( string $label = 'Meta Field', string $placeholder = 'meta_key' ): array {
		return [
			'label'       => esc_html__( $label, 'elementor-dynamic-toolkit' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'placeholder' => esc_html__( $placeholder, 'elementor-dynamic-toolkit' ),
		];
	}
}
