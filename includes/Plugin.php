<?php
/**
 * Main plugin application.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT;

defined( 'ABSPATH' ) || exit;

final class Plugin {

	private static ?self $instance = null;

	private bool $hooks_registered = false;

	private function __construct() {}

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function run(): void {
		if ( $this->hooks_registered ) {
			return;
		}

		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ], 20 );
		add_action( 'admin_notices', [ $this, 'maybe_show_elementor_notice' ] );

		$this->hooks_registered = true;
	}

	public static function activate(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		flush_rewrite_rules();
	}

	public static function deactivate(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		flush_rewrite_rules();
	}

	public function on_plugins_loaded(): void {
		load_plugin_textdomain(
			Constants::TEXT_DOMAIN,
			false,
			dirname( Constants::BASENAME ) . '/languages'
		);

		if ( class_exists( '\\EDT\\Developer\\Hooks' ) ) {
			\EDT\Developer\Hooks::register();
		}

		if ( class_exists( '\\EDT\\Admin\\Admin' ) ) {
			( new \EDT\Admin\Admin() )->register();
		}

		if ( ! $this->is_supported_elementor_available() ) {
			return;
		}

		( new Elementor\Elementor() )->register();

		if ( class_exists( '\\EDT\\Integrations\\ACF' ) ) {
			( new \EDT\Integrations\ACF() )->register();
		}

		if ( class_exists( '\\EDT\\Integrations\\WooCommerce' ) ) {
			( new \EDT\Integrations\WooCommerce() )->register();
		}

		if ( class_exists( '\\EDT\\Integrations\\ElementorPro' ) ) {
			( new \EDT\Integrations\ElementorPro() )->register();
		}

		if ( class_exists( '\\EDT\\API\\REST' ) ) {
			( new \EDT\API\REST() )->register();
		}
	}

	public function maybe_show_elementor_notice(): void {
		if ( ! current_user_can( 'activate_plugins' ) || $this->is_supported_elementor_available() ) {
			return;
		}

		if ( ! $this->is_elementor_available() ) {
			$message = sprintf(
				/* translators: %s: plugin name. */
				esc_html__( '%s requires Elementor to be installed and activated.', Constants::TEXT_DOMAIN ),
				'<strong>' . esc_html__( 'Elementor Dynamic Toolkit', Constants::TEXT_DOMAIN ) . '</strong>'
			);
		} else {
			$message = sprintf(
				/* translators: 1: plugin name, 2: required Elementor version. */
				esc_html__( '%1$s requires Elementor %2$s or later.', Constants::TEXT_DOMAIN ),
				'<strong>' . esc_html__( 'Elementor Dynamic Toolkit', Constants::TEXT_DOMAIN ) . '</strong>',
				esc_html( Constants::MINIMUM_ELEMENTOR_VERSION )
			);
		}

		printf( '<div class="notice notice-warning"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	private function is_elementor_available(): bool {
		return defined( 'ELEMENTOR_VERSION' ) && class_exists( '\Elementor\Plugin' );
	}

	private function is_supported_elementor_available(): bool {
		return $this->is_elementor_available() && version_compare(
			ELEMENTOR_VERSION,
			Constants::MINIMUM_ELEMENTOR_VERSION,
			'>='
		);
	}
}

register_activation_hook( Constants::FILE, [ Plugin::class, 'activate' ] );
register_deactivation_hook( Constants::FILE, [ Plugin::class, 'deactivate' ] );