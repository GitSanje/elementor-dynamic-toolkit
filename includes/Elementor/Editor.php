<?php
/**
 * Elementor Editor Integration & Localized Data.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Elementor;

use EDT\Conditions\ConditionManager;
use EDT\Constants;

defined( 'ABSPATH' ) || exit;

final class Editor {

	public function register(): void {
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'localize_editor_data' ] );
	}

	public function localize_editor_data(): void {
		$condition_manager = new ConditionManager();
		$conditions = [];

		foreach ( $condition_manager->get_all() as $id => $condition ) {
			$conditions[ $id ] = [
				'id'        => $condition->get_id(),
				'title'     => $condition->get_title(),
				'group'     => $condition->get_group(),
				'operators' => $condition->get_operators(),
			];
		}

		wp_localize_script(
			'edt-editor',
			'EDT_Editor_Data',
			[
				'restUrl'    => esc_url_raw( rest_url( Constants::REST_NAMESPACE ) ),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
				'conditions' => $conditions,
				'i18n'       => [
					'loading'        => esc_html__( 'Loading...', 'elementor-dynamic-toolkit' ),
					'noResults'      => esc_html__( 'No results found', 'elementor-dynamic-toolkit' ),
					'selectOption'   => esc_html__( 'Select an option', 'elementor-dynamic-toolkit' ),
					'typeToSearch'   => esc_html__( 'Type to search...', 'elementor-dynamic-toolkit' ),
					'addRule'        => esc_html__( 'Add Rule', 'elementor-dynamic-toolkit' ),
					'removeRule'     => esc_html__( 'Remove Rule', 'elementor-dynamic-toolkit' ),
				],
			]
		);
	}
}
