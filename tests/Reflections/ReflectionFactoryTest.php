<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Tests\Reflections;

use DI\Annotation\Inject;
use WpHookAnnotations\Reflections\ReflectionFactory;
use ReflectionException;
use WpHookAnnotations\Tests\ContainerTestCase;

class ReflectionFactoryTest extends ContainerTestCase {

	/**
	 * @var ReflectionFactory $reflection_factory
	 * @Inject()
	 */
	private $reflection_factory;

	/**
	 * @throws ReflectionException
	 */
	public function testGetAllReflectors(): void {
		$reflectors = $this->reflection_factory->getAllReflectors( $this );
		// 5 = 1 class, 1 prop, and 3 methods
		// This will change if more tests or properties are added
		// ignores inherited stuff
		$this->assertCount( 5, $reflectors );
	}

	/**
	 * @throws ReflectionException
	 */
	public function testGetMethodReflectors(): void {
		$reflectors = $this->reflection_factory->getMethodReflectors( $this );
		// 3 methods
		// This will change if more methods are added
		// ignores inherited stuff
		$this->assertCount( 3, $reflectors );
	}

	/**
	 * @throws ReflectionException
	 */
	public function testGetPropertyReflectors(): void {
		$reflectors = $this->reflection_factory->getPropertyReflectors( $this );
		// 1 prop
		// ignores inherited stuff
		$this->assertCount( 1, $reflectors );
	}
}