<?php
namespace EDT\Conditions;

defined( 'ABSPATH' ) || exit;

final class PostCondition implements ConditionInterface {
	public function __construct( private readonly string $post_type ) {}

	public function evaluate( array $context = [] ): bool {
		$post_id = absint( $context['post_id'] ?? get_the_ID() );
		return $post_id > 0 && $this->post_type === get_post_type( $post_id );
	}
}