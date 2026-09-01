<?php
/**
 * Elementor Custom Control for Async Search Select with debouncing.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

defined( 'ABSPATH' ) || exit;

if ( class_exists( '\Elementor\Base_Data_Control' ) ) {
	final class AsyncSelectControl extends \Elementor\Base_Data_Control {

		public const TYPE = 'edt_async_select';

		public function get_type(): string {
			return self::TYPE;
		}

		public function enqueue(): void {
			wp_enqueue_script( 'edt-editor' );
			wp_enqueue_style( 'edt-editor' );
		}

		protected function get_default_settings(): array {
			return [
				'options'      => [],
				'multiple'     => false,
				'source'       => 'posts',
				'post_type'    => 'post',
				'placeholder'  => esc_html__( 'Type to search...', 'elementor-dynamic-toolkit' ),
			];
		}

		public function content_template(): void {
			$control_uid = $this->get_control_uid();
			?>
			<div class="elementor-control-field">
				<# if ( data.label ) { #>
					<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
				<# } #>
				<div class="elementor-control-input-wrapper elementor-control-unit-5">
					<select id="<?php echo esc_attr( $control_uid ); ?>"
						class="edt-async-select-field"
						data-setting="{{ data.name }}"
						data-source="{{ data.source || 'posts' }}"
						data-post-type="{{ data.post_type || 'post' }}"
						data-placeholder="{{ data.placeholder || '' }}"
						<# if ( data.multiple ) { #> multiple <# } #>>
						<# if ( data.controlValue ) { #>
							<# if ( _.isArray( data.controlValue ) ) { #>
								<# _.each( data.controlValue, function( val ) { #>
									<option value="{{ val }}" selected>{{ data.options[val] || val }}</option>
								<# } ); #>
							<# } else { #>
								<option value="{{ data.controlValue }}" selected>{{ data.options[data.controlValue] || data.controlValue }}</option>
							<# } #>
						<# } #>
					</select>
				</div>
			</div>
			<# if ( data.description ) { #>
				<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
			<?php
		}
	}
}
