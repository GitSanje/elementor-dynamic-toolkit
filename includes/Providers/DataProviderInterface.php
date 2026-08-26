<?php
/**
 * Contract for dynamic data providers.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Providers;

defined( 'ABSPATH' ) || exit;

interface DataProviderInterface {

	public function get_id(): string;

	public function get_label(): string;

	public function supports( string $field, int $object_id ): bool;

	public function get_fields( int $object_id ): array;

	public function get_value( string $field, int $object_id );
}