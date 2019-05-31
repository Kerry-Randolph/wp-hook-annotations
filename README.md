# wp-hook-annotations
Automatically wires callback functions to Wordpress hooks by using docblock annotations.

For example, instead of putting boilerplate `add_action()`, `add_filter()`, or `add_shortcode()` in the constructor, 
you would simply define it in the docblock of the callback function:

```
/**
  * @Action(tag="wp_loaded",priority=10,accepted_args=1)
  */
public function doSomethingAppropriate(){
  // do something completely approprite
}
```

`@Filter` accepts the same parameters as `@Action`.

`@Shortcode` accepts only the `tag` param: `@Shortcode(tag="some_wp_sc")`

The `priority` and `accepted_args` parameters are optional, and default to 10 and 1 respectively, so this is valid:

```
/**
  * @Filter(tag="some_wp_filter")
  */
public function updateSomeValue(string $value): string {
  return 'updated';
}
```

You can wire multiple hooks to a single callback function:

```
/**
  * @Filter(tag="some_wp_filter")
  * @Action(tag="some_wp_action")
  * @Filter(tag="another_wp_filter")
  */
public function updateSomeValue(string $value): string {
  return 'updated';
}
```

Once the hooks are defined in the docblocks, you need to get the `HookManager` object to process them.

The easiest way is by using the provided `HookAware` trait:

```
class MyWordpressHookClass {
  use HookAware;
}
```

The `HookAware->processHooks` method is triggered automatically by the DI container, and uses reflection to discover the hooks and wire them into Wordpress.

Alternatively, you could get the `HookManager` in the constructor via DI, and manually trigger `processHooks`:
```
__construct( HookManager $hook_manager ) {
  $hook_manager->processHooks( $this );
}
```

NOTE: Double quotes are required. This is not valid: `@Shortcode(tag='some_wp_tag')`
