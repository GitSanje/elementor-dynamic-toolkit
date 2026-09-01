<?php
/**
 * Elementor Custom Control for Visual Condition Rule Builder.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

defined( 'ABSPATH' ) || exit;

if ( class_exists( '\Elementor\Base_Data_Control' ) ) {
	final class ConditionBuilderControl extends \Elementor\Base_Data_Control {

		public const TYPE = 'edt_condition_builder';

		public function get_type(): string {
			return self::TYPE;
		}

		public function enqueue(): void {
			wp_enqueue_script( 'edt-editor' );
			wp_enqueue_style( 'edt-editor' );
		}

		protected function get_default_settings(): array {
			return [
				'conditions' => [],
			];
		}

		public function content_template(): void {
			$control_uid = $this->get_control_uid();
			?>
			<div class="edt-condition-builder-control">
				<div class="elementor-control-field">
					<label class="elementor-control-title"><?php esc_html_e( 'Rule Logic', 'elementor-dynamic-toolkit' ); ?></label>
					<div class="edt-condition-builder-header">
						<select class="edt-condition-builder__relation" data-setting="relation">
							<option value="AND"><?php esc_html_e( 'AND (All rules must match)', 'elementor-dynamic-toolkit' ); ?></option>
							<option value="OR"><?php esc_html_e( 'OR (Any rule can match)', 'elementor-dynamic-toolkit' ); ?></option>
						</select>
					</div>
				</div>

				<div class="edt-condition-rules-list"></div>

				<div class="edt-condition-builder-actions">
					<button type="button" class="elementor-button elementor-button-default edt-add-condition-btn">
						<i class="eicon-plus" aria-hidden="true"></i> <?php esc_html_e( 'Add Rule', 'elementor-dynamic-toolkit' ); ?>
					</button>
				</div>
			</div>
			<?php
		}
	}
}
