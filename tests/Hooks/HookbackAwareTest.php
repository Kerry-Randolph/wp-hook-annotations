<?php
declare( strict_types=1 );

namespace Tests\Hooks;

use DI\DependencyException;
use HookAnnotations\DIContainer\ContainerAware;
use HookAnnotations\Hooks\HookAware;
use HookAnnotations\Hooks\Model\Action;
use HookAnnotations\Hooks\Model\Filter;
use HookAnnotations\Hooks\Model\Shortcode;
use PHPUnit\Framework\TestCase;
use Tests\ContainerTestCase;

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
