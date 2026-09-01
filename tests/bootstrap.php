<?php

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__, 4 ) . DIRECTORY_SEPARATOR );
}

if ( ! defined( 'MINUTE_IN_SECONDS' ) ) {
	define( 'MINUTE_IN_SECONDS', 60 );
}

if ( ! defined( 'HOUR_IN_SECONDS' ) ) {
	define( 'HOUR_IN_SECONDS', 3600 );
}

if ( ! defined( 'DAY_IN_SECONDS' ) ) {
	define( 'DAY_IN_SECONDS', 86400 );
}

if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

if ( ! defined( 'EDT_PLUGIN_URL' ) ) {
	define( 'EDT_PLUGIN_URL', 'http://example.test/wp-content/plugins/elementor-dynamic-toolkit/' );
}

if ( ! function_exists( 'plugin_dir_path' ) ) {
	function plugin_dir_path( string $file ): string {
		return trailingslashit( dirname( $file ) );
	}
}

if ( ! function_exists( 'plugin_dir_url' ) ) {
	function plugin_dir_url( string $file ): string {
		return 'http://example.test/wp-content/plugins/elementor-dynamic-toolkit/';
	}
}

if ( ! function_exists( 'plugin_basename' ) ) {
	function plugin_basename( string $file ): string {
		return basename( dirname( $file ) ) . '/' . basename( $file );
	}
}

if ( ! function_exists( 'trailingslashit' ) ) {
	function trailingslashit( string $value ): string {
		return rtrim( $value, '/\\' ) . '/';
	}
}

if ( ! function_exists( 'register_activation_hook' ) ) {
	function register_activation_hook( string $file, callable $callback ): void {}
}

if ( ! function_exists( 'register_deactivation_hook' ) ) {
	function register_deactivation_hook( string $file, callable $callback ): void {}
}

if ( ! function_exists( 'sanitize_key' ) ) {
	function sanitize_key( string $key ): string {
		return strtolower( preg_replace( '/[^a-z0-9_\-]/i', '', $key ) ?? '' );
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( string $str ): string {
		return trim( strip_tags( $str ) );
	}
}

if ( ! function_exists( 'absint' ) ) {
	function absint( mixed $maybeint ): int {
		return abs( (int) $maybeint );
	}
}

if ( ! function_exists( 'wp_parse_args' ) ) {
	function wp_parse_args( mixed $args, mixed $defaults = [] ): array {
		if ( is_object( $args ) ) {
			$r = get_object_vars( $args );
		} elseif ( is_array( $args ) ) {
			$r =& $args;
		} else {
			$r = [];
		}

		if ( is_array( $defaults ) ) {
			return array_merge( $defaults, $r );
		}

		return $r;
	}
}

if ( ! function_exists( 'apply_filters' ) ) {
	function apply_filters( string $hook_name, mixed $value, mixed ...$args ): mixed {
		return $value;
	}
}

if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return true;
	}
}

if ( ! function_exists( 'add_action' ) ) {
	function add_action( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return true;
	}
}

if ( ! function_exists( 'do_action' ) ) {
	function do_action( string $hook_name, mixed ...$arg ): void {}
}

if ( ! function_exists( 'esc_html__' ) ) {
	function esc_html__( string $text, string $domain = 'default' ): string {
		return $text;
	}
}

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( string $text ): string {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( string $text ): string {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( string $url ): string {
		return filter_var( $url, FILTER_SANITIZE_URL ) ?: '';
	}
}

if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( mixed $data, int $options = 0, int $depth = 512 ): string|false {
		return json_encode( $data, $options, $depth );
	}
}

if ( ! function_exists( 'get_option' ) ) {
	$GLOBALS['__mock_wp_options'] = [];
	function get_option( string $option, mixed $default = false ): mixed {
		return $GLOBALS['__mock_wp_options'][ $option ] ?? $default;
	}
}

if ( ! function_exists( 'update_option' ) ) {
	function update_option( string $option, mixed $value, mixed $autoload = null ): bool {
		$GLOBALS['__mock_wp_options'][ $option ] = $value;
		return true;
	}
}

if ( ! function_exists( 'wp_cache_get' ) ) {
	$GLOBALS['__mock_wp_cache'] = [];
	function wp_cache_get( string $key, string $group = '' ): mixed {
		return $GLOBALS['__mock_wp_cache'][ $group . ':' . $key ] ?? false;
	}
}

if ( ! function_exists( 'wp_cache_set' ) ) {
	function wp_cache_set( string $key, mixed $data, string $group = '', int $expire = 0 ): bool {
		$GLOBALS['__mock_wp_cache'][ $group . ':' . $key ] = $data;
		return true;
	}
}

if ( ! function_exists( 'wp_cache_delete' ) ) {
	function wp_cache_delete( string $key, string $group = '' ): bool {
		unset( $GLOBALS['__mock_wp_cache'][ $group . ':' . $key ] );
		return true;
	}
}

if ( ! function_exists( 'wp_list_pluck' ) ) {
	function wp_list_pluck( array $list, string|int $field ): array {
		$newlist = [];
		foreach ( $list as $key => $value ) {
			if ( is_object( $value ) ) {
				$newlist[ $key ] = $value->$field ?? null;
			} elseif ( is_array( $value ) ) {
				$newlist[ $key ] = $value[ $field ] ?? null;
			}
		}
		return $newlist;
	}
}

if ( ! class_exists( 'WP_Query' ) ) {
	class WP_Query {
		public array $posts = [];
		public int $found_posts = 0;
		public int $post_count = 0;
		public int $max_num_pages = 1;
		public function __construct( public array $query_vars = [] ) {}
		public function have_posts(): bool { return ! empty( $this->posts ); }
		public function the_post(): void {}
	}
}

if ( ! class_exists( 'WP_Post' ) ) {
	class WP_Post {
		public int $ID = 1;
		public string $post_title = 'Sample Post';
		public string $post_excerpt = 'Sample Excerpt';
		public string $post_type = 'post';
		public int $post_author = 1;
	}
}

require_once dirname( __DIR__ ) . '/includes/Constants.php';
require_once dirname( __DIR__ ) . '/includes/Autoloader.php';
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

\EDT\Autoloader::register();