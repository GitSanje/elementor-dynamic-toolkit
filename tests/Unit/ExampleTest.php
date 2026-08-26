<?php
/**
 * Basic PHPUnit smoke test for the plugin package.
 */

namespace EDT\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase {
	public function test_plugin_constants_are_available(): void {
		$this->assertSame( 'elementor-dynamic-toolkit', \EDT\Constants::TEXT_DOMAIN );
		$this->assertGreaterThanOrEqual( 1, version_compare( PHP_VERSION, '8.1.0', '>=' ) );
	}
}
