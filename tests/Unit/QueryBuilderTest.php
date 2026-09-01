<?php

namespace EDT\Tests\Unit;

use EDT\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class QueryBuilderTest extends TestCase {

	public function test_fluent_builder_sets_basic_parameters(): void {
		$builder = new QueryBuilder();
		$builder->post_type( 'product' )
			->posts_per_page( 12 )
			->order_by( 'title' )
			->ascending()
			->offset( 5 );

		$args = $builder->get_args();

		$this->assertSame( 'product', $args['post_type'] );
		$this->assertSame( 12, $args['posts_per_page'] );
		$this->assertSame( 'title', $args['orderby'] );
		$this->assertSame( 'ASC', $args['order'] );
		$this->assertSame( 5, $args['offset'] );
	}

	public function test_fluent_builder_handles_tax_and_meta_queries(): void {
		$builder = new QueryBuilder();
		$builder->post_type( 'post' )
			->where_taxonomy( 'category', 'tech', 'IN' )
			->where_meta( 'is_featured', '1', '=' );

		$args = $builder->get_args();

		$this->assertNotEmpty( $args['tax_query'] );
		$this->assertNotEmpty( $args['meta_query'] );
		$this->assertSame( 'tech', $args['tax_query'][0]['terms'] );
		$this->assertSame( 'is_featured', $args['meta_query'][0]['key'] );
	}

	public function test_to_definition_returns_query_definition(): void {
		$builder = new QueryBuilder();
		$builder->post_type( 'page' )->paginate( 3, 10 );

		$definition = $builder->to_definition();

		$this->assertSame( 'page', $definition->get_post_type() );
		$this->assertSame( 10, $definition->get_posts_per_page() );
		$this->assertSame( 3, $definition->get_page() );
	}
}
