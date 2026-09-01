<?php
/**
 * Render Context Value Object.
 *
 * @package ElementorDynamicToolkit
 */

namespace EDT\Rendering;

use EDT\Query\QueryResult;

defined( 'ABSPATH' ) || exit;

final class RenderContext {

	/**
	 * @param array<string, mixed> $settings
	 * @param array<string, mixed> $attributes
	 */
	public function __construct(
		private readonly array $settings = [],
		private readonly ?QueryResult $result = null,
		private readonly array $attributes = [],
		private readonly mixed $current_item = null,
		private readonly int $current_index = 0
	) {}

	public function get_settings(): array {
		return $this->settings;
	}

	public function get_setting( string $key, mixed $default = null ): mixed {
		return $this->settings[ $key ] ?? $default;
	}

	public function get_result(): ?QueryResult {
		return $this->result;
	}

	public function get_current_item(): mixed {
		return $this->current_item;
	}

	public function get_current_index(): int {
		return $this->current_index;
	}

	public function get_attributes(): array {
		return $this->attributes;
	}

	public function with_item( mixed $item, int $index ): self {
		return new self(
			$this->settings,
			$this->result,
			$this->attributes,
			$item,
			$index
		);
	}
}
