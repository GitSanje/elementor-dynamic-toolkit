=== Elementor Dynamic Toolkit ===
Contributors: edt
Tags: elementor, dynamic content, queries
Requires at least: 6.0
Requires PHP: 8.1
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A foundation for dynamic data and query-powered Elementor extensions.

== Description ==

Elementor Dynamic Toolkit is being developed as a reusable framework for dynamic data, WordPress queries, conditional visibility, and Elementor integrations.

The toolkit provides a reusable query engine, dynamic data providers, Elementor
Dynamic Tags, conditional visibility services, and query-driven widgets.

== Requirements ==

* WordPress 6.0 or later.
* PHP 8.1 or later.
* Elementor 3.20.0 or later.

== Installation ==

1. Upload the `elementor-dynamic-toolkit` directory to `/wp-content/plugins/`.
2. Activate Elementor.
3. Activate Elementor Dynamic Toolkit from the Plugins screen.

== Development ==

Install development dependencies with `composer install`.

== Developer API ==

The following filters are public extension points:

* `edt/query/args` - modify validated query arguments before execution.
* `edt/query/results` - modify the resulting `WP_Query` object.
* `edt/data_providers` - register or replace dynamic data providers.
* `edt/query_providers` - register custom `QueryProviderInterface` instances.
* `edt/dynamic_fields` - add or remove Dynamic Tag instances.
* `edt/conditions` - register or replace condition instances.
* `edt/widgets` - add or remove Elementor widget instances before registration.
* `edt/query/cache_ttl` - change query result cache lifetime in seconds.

The query selector controls use the registered `edt_query_select` Elementor
control type, backed by Elementor's native select control implementation.

Example:

```php
add_filter( 'edt/query/args', static function ( $args ) {
	$args['posts_per_page'] = min( 12, (int) $args['posts_per_page'] );
	return $args;
} );
```

All callback values remain subject to WordPress and plugin validation. Custom
extensions should validate their own input and escape output at render time.

Custom query providers implement `EDT\Providers\QueryProviderInterface`. The
first provider that returns `true` from `supports()` supplies query arguments;
those arguments are validated before execution.