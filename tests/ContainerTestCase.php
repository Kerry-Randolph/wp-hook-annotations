<?php
declare( strict_types=1 );

namespace Tests;

use DI\Container;
use DI\DependencyException;
use PHPUnit\Framework\TestCase;

class ContainerTestCase extends TestCase {

	/**
	 * @throws DependencyException
	 */
	public function setUp(): void {
		/** @var Container $wp_hook_annotations_container */
		global $wp_hook_annotations_container;
		$wp_hook_annotations_container->injectOn( $this );
	}
}