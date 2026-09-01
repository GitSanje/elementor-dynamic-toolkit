<?php
/**
 * Standard WordPress Query Provider.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers\Query;

use EDT\Providers\QueryProviderInterface;

defined( 'ABSPATH' ) || exit;

final class WPQueryProvider implements QueryProviderInterface {

	public function get_id(): string {
		return 'wp_query';
	}

	public function get_label(): string {
		return esc_html__( 'Standard WordPress Query', 'elementor-dynamic-toolkit' );
	}

	public function supports( array $args ): bool {
		return true; // Default fallback provider
	}

	public function get_query_args( array $args ): array {
		return $args;
	}
}
