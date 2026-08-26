<?php
/**
 * Bootstrap integration smoke test.
 */

namespace EDT\Tests\Integration;

use PHPUnit\Framework\TestCase;

final class BootstrapTest extends TestCase {
	public function test_autoloader_resolves_plugin_classes(): void {
		$this->assertTrue( class_exists( '\\EDT\\Plugin' ) );
		$this->assertTrue( class_exists( '\\EDT\\Query\\QueryBuilder' ) );
		$this->assertTrue( class_exists( '\\EDT\\Providers\\QueryProviderManager' ) );
		$this->assertTrue( class_exists( '\\EDT\\Providers\\WooCommerceProvider' ) );
		$this->assertTrue( class_exists( '\\EDT\\Elementor\\Controls' ) );
	}
}
