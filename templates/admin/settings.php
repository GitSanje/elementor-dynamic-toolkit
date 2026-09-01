<?php
/**
 * Admin Settings Template.
 *
 * @package ElementorDynamicToolkit
 */

defined( 'ABSPATH' ) || exit;

$tabs = [
	'general'     => esc_html__( 'General', 'elementor-dynamic-toolkit' ),
	'performance' => esc_html__( 'Performance & Caching', 'elementor-dynamic-toolkit' ),
	'integrations'=> esc_html__( 'Integrations', 'elementor-dynamic-toolkit' ),
	'debug'       => esc_html__( 'Developer & Debug', 'elementor-dynamic-toolkit' ),
];
?>
<div class="wrap edt-admin-wrap">
	<div class="edt-admin-header">
		<h1 class="edt-admin-title">
			<span class="dashicons dashicons-layout" style="font-size: 32px; width: 32px; height: 32px; vertical-align: middle; margin-right: 8px; color: #4338ca;"></span>
			<?php echo esc_html__( 'Elementor Dynamic Toolkit', 'elementor-dynamic-toolkit' ); ?>
			<span class="edt-admin-badge">v1.0.0</span>
		</h1>
		<p class="edt-admin-subtitle">
			<?php echo esc_html__( 'Enterprise-grade dynamic content, query builder, and conditional visibility framework for Elementor.', 'elementor-dynamic-toolkit' ); ?>
		</p>
	</div>

	<nav class="nav-tab-wrapper edt-admin-tabs">
		<?php foreach ( $tabs as $tab_key => $tab_label ) : ?>
			<a href="<?php echo esc_url( add_query_arg( 'tab', $tab_key ) ); ?>" class="nav-tab <?php echo ( $active_tab === $tab_key ) ? 'nav-tab-active' : ''; ?>">
				<?php echo esc_html( $tab_label ); ?>
			</a>
		<?php endforeach; ?>
	</nav>

	<form method="post" action="options.php" class="edt-admin-form">
		<?php settings_fields( 'edt_settings' ); ?>

		<?php if ( 'general' === $active_tab ) : ?>
			<div class="edt-admin-card">
				<h2><?php esc_html_e( 'General Preferences', 'elementor-dynamic-toolkit' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="edt_asset_mode"><?php esc_html_e( 'Frontend Asset Loading', 'elementor-dynamic-toolkit' ); ?></label>
						</th>
						<td>
							<select id="edt_asset_mode" name="edt_settings[asset_mode]">
								<option value="smart" <?php selected( ( $settings['asset_mode'] ?? '' ) === 'smart' ); ?>><?php esc_html_e( 'Smart (Load only on pages with EDT widgets)', 'elementor-dynamic-toolkit' ); ?></option>
								<option value="all" <?php selected( ( $settings['asset_mode'] ?? '' ) === 'all' ); ?>><?php esc_html_e( 'Always Load (Everywhere)', 'elementor-dynamic-toolkit' ); ?></option>
							</select>
							<p class="description"><?php esc_html_e( 'Smart loading optimizes initial page payload and Google Core Web Vitals.', 'elementor-dynamic-toolkit' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="edt_enable_async_ctrl"><?php esc_html_e( 'Editor Async Lookups', 'elementor-dynamic-toolkit' ); ?></label>
						</th>
						<td>
							<label>
								<input type="checkbox" id="edt_enable_async_ctrl" name="edt_settings[enable_async_ctrl]" value="1" <?php checked( ! empty( $settings['enable_async_ctrl'] ) ); ?> />
								<?php esc_html_e( 'Enable debounced async REST searches in Elementor editor controls', 'elementor-dynamic-toolkit' ); ?>
							</label>
						</td>
					</tr>
				</table>
			</div>

		<?php elseif ( 'performance' === $active_tab ) : ?>
			<div class="edt-admin-card">
				<h2><?php esc_html_e( 'Query Caching & Optimization', 'elementor-dynamic-toolkit' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="edt_enable_cache"><?php esc_html_e( 'Query Result Caching', 'elementor-dynamic-toolkit' ); ?></label>
						</th>
						<td>
							<label>
								<input type="checkbox" id="edt_enable_cache" name="edt_settings[enable_cache]" value="1" <?php checked( ! empty( $settings['enable_cache'] ) ); ?> />
								<?php esc_html_e( 'Enable WordPress object cache for database query results', 'elementor-dynamic-toolkit' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="edt_cache_ttl"><?php esc_html_e( 'Cache Expiration (TTL)', 'elementor-dynamic-toolkit' ); ?></label>
						</th>
						<td>
							<input type="number" id="edt_cache_ttl" name="edt_settings[cache_ttl]" value="<?php echo esc_attr( $settings['cache_ttl'] ?? 300 ); ?>" min="30" max="86400" step="30" />
							<span class="description"><?php esc_html_e( 'seconds (default: 300 = 5 minutes). Automatic cache invalidation triggers when posts are created, updated, or deleted.', 'elementor-dynamic-toolkit' ); ?></span>
						</td>
					</tr>
				</table>
			</div>

		<?php elseif ( 'integrations' === $active_tab ) : ?>
			<div class="edt-admin-card">
				<h2><?php esc_html_e( 'Third-Party Plugin Integrations', 'elementor-dynamic-toolkit' ); ?></h2>
				<p><?php esc_html_e( 'Detected integrations and dynamic data providers active on this site:', 'elementor-dynamic-toolkit' ); ?></p>
				<ul style="list-style: disc; margin-left: 20px; line-height: 2;">
					<li>
						<strong>Elementor Core:</strong>
						<span style="color: green;">✔ <?php echo esc_html( defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : 'Active' ); ?></span>
					</li>
					<li>
						<strong>Advanced Custom Fields (ACF):</strong>
						<?php if ( function_exists( 'get_field' ) ) : ?>
							<span style="color: green;">✔ <?php esc_html_e( 'Detected & Active', 'elementor-dynamic-toolkit' ); ?></span>
						<?php else : ?>
							<span style="color: #666;"><?php esc_html_e( 'Not Detected (Optional)', 'elementor-dynamic-toolkit' ); ?></span>
						<?php endif; ?>
					</li>
					<li>
						<strong>WooCommerce:</strong>
						<?php if ( function_exists( 'WC' ) ) : ?>
							<span style="color: green;">✔ <?php esc_html_e( 'Detected & Active', 'elementor-dynamic-toolkit' ); ?></span>
						<?php else : ?>
							<span style="color: #666;"><?php esc_html_e( 'Not Detected (Optional)', 'elementor-dynamic-toolkit' ); ?></span>
						<?php endif; ?>
					</li>
				</ul>
			</div>

		<?php elseif ( 'debug' === $active_tab ) : ?>
			<div class="edt-admin-card">
				<h2><?php esc_html_e( 'Developer Diagnostics & Debugging', 'elementor-dynamic-toolkit' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="edt_enable_debug"><?php esc_html_e( 'Debug Logging', 'elementor-dynamic-toolkit' ); ?></label>
						</th>
						<td>
							<label>
								<input type="checkbox" id="edt_enable_debug" name="edt_settings[enable_debug]" value="1" <?php checked( ! empty( $settings['enable_debug'] ) ); ?> />
								<?php esc_html_e( 'Write query building and condition execution logs to standard WordPress debug.log', 'elementor-dynamic-toolkit' ); ?>
							</label>
						</td>
					</tr>
				</table>
			</div>
		<?php endif; ?>

		<div style="margin-top: 24px;">
			<?php submit_button(); ?>
		</div>
	</form>
</div>
<style>
.edt-admin-wrap { max-width: 900px; margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; }
.edt-admin-header { margin-bottom: 20px; padding: 10px 0; }
.edt-admin-title { font-size: 24px; font-weight: 700; color: #1e1e2f; margin-bottom: 4px; }
.edt-admin-badge { font-size: 12px; background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 9999px; vertical-align: middle; margin-left: 6px; }
.edt-admin-subtitle { color: #64748b; font-size: 14px; margin: 0; }
.edt-admin-tabs { margin-bottom: 24px; border-bottom: 1px solid #e2e8f0; }
.edt-admin-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
.edt-admin-card h2 { font-size: 18px; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; color: #0f172a; }
</style>
