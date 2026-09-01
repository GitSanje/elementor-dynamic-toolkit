<?php
/**
 * Settings page rendering for the plugin.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Admin;

use EDT\Constants;

defined( 'ABSPATH' ) || exit;

final class SettingsPage {

	public function render(): void {
		$settings = Settings::get();
		$active_tab = sanitize_key( (string) ( $_GET['tab'] ?? 'general' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		include Constants::DIR . 'templates/admin/settings.php';
	}
}
