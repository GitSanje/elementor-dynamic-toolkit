<?php
/**
 * Contract for widget rendering services.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Rendering;

defined( 'ABSPATH' ) || exit;

interface RendererInterface {

	public function render( string $template, array $data = [] ): void;

	public function get_template_content( string $template, array $data = [] ): string;
}
