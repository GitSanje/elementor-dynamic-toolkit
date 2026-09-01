<?php
/**
 * Meta Field Control Helper.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Controls;

use EDT\Providers\ProviderManager;

defined( 'ABSPATH' ) || exit;

final class MetaFieldControl {

	public static function get_fields( int $post_id = 0 ): array {
		$provider = ( new ProviderManager() )->get( 'core' );
		return $provider ? $provider->get_fields( $post_id ) : [];
	}
}
