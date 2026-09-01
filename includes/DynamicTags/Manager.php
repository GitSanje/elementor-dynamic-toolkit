<?php
/**
 * Dynamic tag manager for the toolkit.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

use EDT\Constants;

defined( 'ABSPATH' ) || exit;

final class Manager {

	private static ?self $instance = null;

	/**
	 * @var array<int, \Elementor\Core\DynamicTags\Tag>
	 */
	private array $tags = [];

	private bool $loaded = false;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function load_default_tags(): void {
		if ( $this->loaded ) {
			return;
		}

		$this->register_tag( new CustomFieldTag() );
		$this->register_tag( new TaxonomyTag() );
		$this->register_tag( new RelatedPostTag() );
		$this->register_tag( new OptionFieldTag() );
		$this->register_tag( new UserTag() );

		if ( function_exists( 'get_field' ) ) {
			$this->register_tag( new ACFFieldTag() );
		}

		$this->loaded = true;
	}

	public function register_tag( mixed $tag ): void {
		if ( $tag instanceof \Elementor\Core\DynamicTags\Tag ) {
			$this->tags[] = $tag;
		}
	}

	/**
	 * @return array<int, \Elementor\Core\DynamicTags\Tag>
	 */
	public function get_tags(): array {
		return $this->tags;
	}

	public function register( mixed $elementor_manager ): void {
		if ( ! is_object( $elementor_manager ) || ! method_exists( $elementor_manager, 'register_group' ) ) {
			return;
		}

		$this->load_default_tags();
		$tags = apply_filters( 'edt/dynamic_fields', $this->get_tags() );

		$elementor_manager->register_group(
			'edt',
			[
				'title' => esc_html__( 'Elementor Dynamic Toolkit', 'elementor-dynamic-toolkit' ),
			]
		);

		foreach ( is_array( $tags ) ? $tags : [] as $tag ) {
			if ( $tag instanceof \Elementor\Core\DynamicTags\Tag && method_exists( $elementor_manager, 'register' ) ) {
				$elementor_manager->register( $tag );
			}
		}
	}
}
