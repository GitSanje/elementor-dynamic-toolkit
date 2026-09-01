<?php
/**
 * Dynamic Field Control Helper.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

use EDT\Providers\ProviderManager;

defined( 'ABSPATH' ) || exit;

final class DynamicFieldControl {

	public static function get_provider_fields( string $provider_id, int $object_id = 0 ): array {
		$provider = ( new ProviderManager() )->get( $provider_id );
		return $provider ? $provider->get_fields( $object_id ) : [];
	}
}
