<?php
declare( strict_types=1 );

namespace Tests\Reflections;

use DI\Annotation\Inject;
use HookAnnotations\Reflections\ReflectionFactory;
use ReflectionException;
use Tests\ContainerTestCase;

class ReflectionFactoryTest extends ContainerTestCase {

	/**
	 * @var ReflectionFactory $reflection_factory
	 * @Inject()
	 */
	private $reflection_factory;

	/**
	 * @throws ReflectionException
	 */
	public function testMakeReflectors(): void {
		$reflectors = $this->reflection_factory->makeReflectors( $this );
		// 3 = 1 class, 1 prop, and 1 method
		// This will change if more tests or properties are added
		// ignores inherited stuff
		$this->assertCount( 3, $reflectors );
	}
}