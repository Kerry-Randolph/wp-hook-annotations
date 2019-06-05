<?php
declare( strict_types=1 );

/**
 * PHPUnit bootstrap file
 *
 */

use Psr\Log\LoggerInterface;
use WpHookAnnotations\Container\Bootstrap;

require_once dirname( __DIR__ ) . '\src\Container\Bootstrap.php';

/**
 * Logs params for testing
 *
 * @param mixed ...$args
 *
 * @throws Exception
 */
function wp_hook_annotations_test_hook_logger( ...$args ): void {
	$container = Bootstrap::getContainer();

	/** @var LoggerInterface $log */
	$log = $container->get( 'test.logger' );

	$log_output = '';
	foreach ( $args as $arg ) {
		if ( is_scalar( $arg ) ) {
			$arg = strval( $arg );
		} else {
			$arg = print_r( $arg, true );
		}
		$log_output .= $arg . ' ';
	}

	$log->info( $log_output );
}

if ( ! function_exists( 'add_filter' ) ) {
	/**
	 * Mock add_filter for testing
	 *
	 * @param     $tag
	 * @param     $function_to_add
	 * @param int $priority
	 * @param int $accepted_args
	 *
	 * @return bool
	 * @throws Exception
	 */
	function add_filter(
		$tag,
		$function_to_add,
		$priority = 10,
		$accepted_args = 1
	) {
		wp_hook_annotations_test_hook_logger( __FUNCTION__, $tag,
			$function_to_add, $priority, $accepted_args );

		return true;
	}
}

if ( ! function_exists( 'add_action' ) ) {
	/**
	 * Mock add_action for testing
	 *
	 * @param     $tag
	 * @param     $function_to_add
	 * @param int $priority
	 * @param int $accepted_args
	 *
	 * @return bool
	 * @throws Exception
	 */
	function add_action(
		$tag,
		$function_to_add,
		$priority = 10,
		$accepted_args = 1
	) {
		wp_hook_annotations_test_hook_logger( __FUNCTION__, $tag,
			$function_to_add, $priority, $accepted_args );

		return true;
	}
}

if ( ! function_exists( 'add_shortcode' ) ) {
	/**
	 * Mock add_shortcode for testing
	 *
	 * @param     $tag
	 * @param     $function_to_add
	 *
	 * @return bool
	 * @throws Exception
	 */
	function add_shortcode(
		$tag,
		$function_to_add
	) {
		wp_hook_annotations_test_hook_logger( __FUNCTION__, $tag,
			$function_to_add );

		return true;
	}
}
