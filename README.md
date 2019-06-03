# Wordpress Hook Annotations

Use PHP Docblock `@annotations` to register WordPress hooks, filters and shortcodes.

[![Latest Stable Version](https://poser.pugx.org/kerryrandolph/wp-hook-annotations/v/stable)](https://packagist.org/packages/kerryrandolph/wp-hook-annotations)
[![Total Downloads](https://poser.pugx.org/kerryrandolph/wp-hook-annotations/downloads)](https://packagist.org/packages/kerryrandolph/wp-hook-annotations)
[![Latest Unstable Version](https://poser.pugx.org/kerryrandolph/wp-hook-annotations/v/unstable)](https://packagist.org/packages/kerryrandolph/wp-hook-annotations)
[![License](https://poser.pugx.org/kerryrandolph/wp-hook-annotations/license)](https://packagist.org/packages/kerryrandolph/wp-hook-annotations)
[![composer.lock](https://poser.pugx.org/kerryrandolph/wp-hook-annotations/composerlock)](https://packagist.org/packages/kerryrandolph/wp-hook-annotations)

## Requirements

- PHP 7.2+
- PHP-DI 6

## Install

Via Composer

```bash
$ composer require kerryrandolph/wp-hook-annotations
```

## Usage

Instead of wiring callbacks with boilerplate `add_action()`, `add_filter()`, or `add_shortcode()`, 
simply add the annotations directly to the callback function's docblock:

```php
/**
  * @Action(tag="wp_loaded",priority=10,accepted_args=1)
  */
public function doSomething(){
  // do something
}
```

The following annotations can be used:

```php
/**
 * @Action(tag="the_hook_name", priority=1, accepted_args=1)
 * @Filter(tag="the_filter_name", priority=1, accepted_args=1)
 * @Shortcode(tag="the_shortcode_name")
 */
```

- The `priority` and `accepted_args` parameters are optional, and default to 10 and 1 respectively
- Double quotes are required, single quotes will throw an exception

You can wire multiple hooks to a single callback function:

```php
/**
  * @Filter(tag="some_wp_filter")
  * @Action(tag="some_wp_action")
  * @Filter(tag="another_wp_filter")
  */
public function updateSomeValue(string $value): string {
  return 'updated';
}
```

Once you have added the hook annotations, you need to get the `HookManager` object to process them.

If you are using Dependency Injection, the easiest way is by using the provided `HookAware` trait:

```php
class MyWordpressHookClass {
  use HookAware;
  
  /**
    * @Action(tag="wp_loaded")
    */
  public function foo(){}
}
```

The `HookAware->processHooks` method is triggered automatically by the DI container, and uses reflection to discover the hooks and wire them into Wordpress.

Alternatively, you could get the `HookManager` in the constructor via DI, and manually trigger `processHooks`:

```php
__construct( HookManager $hook_manager ) {
  $hook_manager->processHooks( $this );
}
```

## License

WP Hook Annotations is released under [the MIT License](LICENSE).