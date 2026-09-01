<?php
/**
 * Related Post Dynamic Tag.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

use EDT\Query\QueryBuilder;

defined( 'ABSPATH' ) || exit;

final class RelatedPostTag extends BaseTag {

	public function get_name(): string {
		return 'edt_related_post';
	}

	public function get_title(): string {
		return esc_html__( 'Related Post Title', 'elementor-dynamic-toolkit' );
	}

	protected function register_controls(): void {
		$this->add_control(
			'relation_type',
			[
				'label'   => esc_html__( 'Relation Type', 'elementor-dynamic-toolkit' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'category' => esc_html__( 'Same Category', 'elementor-dynamic-toolkit' ),
					'author'   => esc_html__( 'Same Author', 'elementor-dynamic-toolkit' ),
				],
				'default' => 'category',
			]
		);

		$this->register_advanced_controls();
	}

	public function render(): void {
		$relation_type = sanitize_key( (string) $this->get_settings( 'relation_type' ) );
		$current_id    = $this->get_post_id();

		if ( ! $current_id ) {
			return;
		}

		$builder = ( new QueryBuilder() )
			->post_type( get_post_type( $current_id ) ?: 'post' )
			->posts_per_page( 1 )
			->exclude_ids( [ $current_id ] );

		if ( 'author' === $relation_type ) {
			$author_id = (int) get_post_field( 'post_author', $current_id );
			if ( $author_id > 0 ) {
				$builder->author( $author_id );
			}
		} else {
			$terms = get_the_terms( $current_id, 'category' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$slugs = wp_list_pluck( $terms, 'slug' );
				$builder->taxonomy( 'category', $slugs );
			}
		}

		$result = $builder->execute();
		$items  = $result->get_items();

		if ( ! empty( $items[0] ) && $items[0] instanceof \WP_Post ) {
			$this->output_value( $items[0]->post_title );
		} else {
			$this->output_value( '' );
		}
	}
}