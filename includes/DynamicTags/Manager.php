<?php
/**
 * Dynamic tag manager for the toolkit.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\DynamicTags;

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

		if ( function_exists( 'get_field' ) ) {
			$this->register_tag( new ACFFieldTag() );
		}

		$this->loaded = true;
	}

	public function register_tag( $tag ): void {
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

	public function register( $elementor_manager ): void {
		if ( ! class_exists( '\\Elementor\\Core\\DynamicTags\\Manager' ) ) {
			return;
		}

		$this->load_default_tags();
		$elementor_manager->register_group(
			'edt',
			[
				'title' => esc_html__( 'Elementor Dynamic Toolkit', 'elementor-dynamic-toolkit' ),
			]
		);

		foreach ( $this->get_tags() as $tag ) {
			$elementor_manager->register( $tag );
		}
	}
}
