<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Tests\Hooks;

use DI\DependencyException;
use WpHookAnnotations\DIContainer\ContainerAware;
use WpHookAnnotations\Hooks\HookAware;
use WpHookAnnotations\Hooks\Model\Action;
use WpHookAnnotations\Hooks\Model\Filter;
use WpHookAnnotations\Hooks\Model\Shortcode;
use PHPUnit\Framework\TestCase;
use WpHookAnnotations\Tests\ContainerTestCase;

class HookbackAwareTest extends ContainerTestCase {
	use HookAware;

	/**
	 * @param string $input
	 *
	 * @return string
	 * @Filter(tag="hatd_test_filter")
	 */
	public function filterFunction( string $input ): string {
		return $input . 'abc123';
	}

	public function testFilter() {
		$this->assertTrue(true);
		// check logs
		// TODO: Figure out how to test against Wordpress core
	}
}
