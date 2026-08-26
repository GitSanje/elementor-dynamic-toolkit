<?php
/**
 * Settings page rendering for the plugin.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Admin;

defined( 'ABSPATH' ) || exit;

final class SettingsPage {

	public function render(): void {
		$settings = Settings::get();
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Elementor Dynamic Toolkit', 'elementor-dynamic-toolkit' ); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'edt_settings' ); ?>
				<?php do_settings_sections( 'elementor-dynamic-toolkit' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="edt_enable_cache"><?php echo esc_html__( 'Enable query caching', 'elementor-dynamic-toolkit' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="edt_enable_cache" name="edt_settings[enable_cache]" value="1" <?php checked( ! empty( $settings['enable_cache'] ) ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="edt_enable_debug"><?php echo esc_html__( 'Enable debug logging', 'elementor-dynamic-toolkit' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="edt_enable_debug" name="edt_settings[enable_debug]" value="1" <?php checked( ! empty( $settings['enable_debug'] ) ); ?> />
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
