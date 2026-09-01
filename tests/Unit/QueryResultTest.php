<?php

namespace EDT\Tests\Unit;

use EDT\Query\QueryResult;
use PHPUnit\Framework\TestCase;

final class QueryResultTest extends TestCase {

	public function test_query_result_encapsulates_pagination_and_items(): void {
		$post1 = new \WP_Post();
		$post2 = new \WP_Post();

		$result = new QueryResult( [ $post1, $post2 ], 20, 2, 4 );

		$this->assertSame( 2, $result->count() );
		$this->assertSame( 20, $result->get_total() );
		$this->assertSame( 2, $result->get_current_page() );
		$this->assertSame( 4, $result->get_total_pages() );
		$this->assertTrue( $result->has_more() );
		$this->assertTrue( $result->has_items() );
	}

	public function test_empty_query_result(): void {
		$result = QueryResult::empty();

		$this->assertSame( 0, $result->count() );
		$this->assertSame( 0, $result->get_total() );
		$this->assertFalse( $result->has_more() );
		$this->assertFalse( $result->has_items() );
	}
}
