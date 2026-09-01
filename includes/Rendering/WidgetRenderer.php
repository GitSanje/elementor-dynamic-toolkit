<?php
/**
 * Widget Template Renderer with Theme Override Support.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Rendering;

use EDT\Constants;

defined( 'ABSPATH' ) || exit;

final class WidgetRenderer implements RendererInterface {

	public function render( string $template, array $data = [] ): void {
		$file = $this->locate_template( $template );
		if ( ! $file || ! file_exists( $file ) ) {
			return;
		}

		extract( $data, EXTR_SKIP );
		include $file;
	}

	public function get_template_content( string $template, array $data = [] ): string {
		$file = $this->locate_template( $template );
		if ( ! $file || ! file_exists( $file ) ) {
			return '';
		}

		ob_start();
		extract( $data, EXTR_SKIP );
		include $file;
		return (string) ob_get_clean();
	}

	public function locate_template( string $template ): ?string {
		$template_name = ltrim( $template, '/' );
		if ( ! str_ends_with( $template_name, '.php' ) ) {
			$template_name .= '.php';
		}

		// 1. Check active theme override: theme/elementor-dynamic-toolkit/{template}.php
		if ( function_exists( 'locate_template' ) ) {
			$theme_file = locate_template( [ 'elementor-dynamic-toolkit/' . $template_name ] );
			if ( ! empty( $theme_file ) ) {
				return $theme_file;
			}
		}

		// 2. Plugin default template
		$plugin_file = Constants::DIR . 'templates/' . $template_name;
		if ( file_exists( $plugin_file ) ) {
			return $plugin_file;
		}

		return null;
	}
}
