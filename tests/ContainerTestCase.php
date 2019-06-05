<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Tests;

use DI\Container;
use DI\DependencyException;
use Exception;
use PHPUnit\Framework\TestCase;
use WpHookAnnotations\Container\Bootstrap;

class ContainerTestCase extends TestCase {

	/**
	 * @throws DependencyException
	 * @throws Exception
	 */
	public function setUp(): void {
		/** @var Container $container */
		$container = Bootstrap::getContainer();
		$container->injectOn( $this );
	}
}