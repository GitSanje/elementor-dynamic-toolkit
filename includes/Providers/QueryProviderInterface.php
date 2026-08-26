<?php
/**
 * Contract for custom query providers.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

interface QueryProviderInterface {

	public function get_id(): string;

	public function get_label(): string;

	public function supports( array $args ): bool;

	public function get_query_args( array $args ): array;
}
