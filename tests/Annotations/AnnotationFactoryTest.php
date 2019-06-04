<?php
declare( strict_types=1 );

namespace Tests\Annotations;

use DI\Annotation\Inject;
use Doctrine\Common\Annotations\AnnotationException;
use HookAnnotations\Annotations\AnnotationFactory;
use HookAnnotations\Hooks\Model\Action;
use HookAnnotations\Hooks\Model\Filter;
use HookAnnotations\Hooks\Model\Hook;
use ReflectionException;
use ReflectionMethod;
use Reflector;
use Tests\ContainerTestCase;

class AnnotationFactoryTest extends ContainerTestCase {

	/**
	 * @var AnnotationFactory
	 * @Inject()
	 */
	private $annotation_factory;

	/**
	 * @Action(tag="test_action")
	 *
	 * @throws ReflectionException
	 * @throws AnnotationException
	 */
	public function testNoAnnotations(): void {
		$object = $this->createMock( 'stdClass' );
		$annotation_objects
		        = $this->annotation_factory->makeAnnotationObjects( $object );
		$this->assertEmpty( $annotation_objects );

		$object             = $this->createMock( 'stdClass' );
		$callback           = function () {
		};
		$annotation_objects = $this->annotation_factory->makeAnnotationObjects(
			$object,
			$callback
		);
		$this->assertEmpty( $annotation_objects );
	}

	/**
	 * @Filter(tag="test_filter",priority=11,accepted_args=2)
	 *
	 * @return void
	 * @throws AnnotationException
	 * @throws ReflectionException
	 */
	public function testGetAnnotations(): void {

		$annotation_objects
			= $this->annotation_factory->makeAnnotationObjects( $this );

		$this->assertCount( 2, $annotation_objects );

		/** @var Action $action */
		$action = $annotation_objects[0];
		$this->assertSame( 'test_action', $action->getTag() );
		$this->assertSame( 10, $action->getPriority() );
		$this->assertSame( 1, $action->getAcceptedArgs() );
		$this->assertIsCallable( $action->getCallback() );

		/** @var Filter $filter */
		$filter = $annotation_objects[1];
		$this->assertSame( 'test_filter', $filter->getTag() );
		$this->assertSame( 11, $filter->getPriority() );
		$this->assertSame( 2, $filter->getAcceptedArgs() );
		$this->assertIsCallable( $filter->getCallback() );
	}

	/**
	 * @throws AnnotationException
	 * @throws ReflectionException
	 */
	public function testAnnotationFilter(): void {
		$filtered
			= $this->annotation_factory->makeAnnotationObjects( $this, null,
			Action::class );

		$this->assertCount( 1, $filtered );

		/** @var Action $action */
		$action = $filtered[0];
		$this->assertSame( 'test_action', $action->getTag() );
		$this->assertSame( 10, $action->getPriority() );
		$this->assertSame( 1, $action->getAcceptedArgs() );
		$this->assertIsCallable( $action->getCallback() );

		$filtered
			= $this->annotation_factory->makeAnnotationObjects( $this, null,
			Filter::class );

		// 2, because action is subclass of filter
		$this->assertCount( 2, $filtered );

		/** @var Filter $filter */
		$filter = $filtered[1];
		$this->assertSame( 'test_filter', $filter->getTag() );
		$this->assertSame( 11, $filter->getPriority() );
		$this->assertSame( 2, $filter->getAcceptedArgs() );
		$this->assertIsCallable( $filter->getCallback() );
	}

	/**
	 * @throws AnnotationException
	 * @throws ReflectionException
	 */
	public function testCallback(): void {

		$source_object = $this;

		$closure = static function (
			object $hookback,
			Reflector $reflector
		) use ( $source_object ): void {
			if ( $hookback instanceof Hook
			     && $reflector instanceof ReflectionMethod
			) {
				$method_name = $reflector->getName();
				$hookback->setCallback( [ $source_object, $method_name ] );
			}
		};

		$filtered
			= $this->annotation_factory->makeAnnotationObjects(
			$source_object, $closure, Hook::class );

		$this->assertIsArray( $filtered );
		$this->assertCount( 2, $filtered );

		/** @var Action $action */
		$action   = $filtered[0];
		$callback = $action->getCallback();
		$this->assertIsArray( $callback );
		$callback_object = $callback[0];
		$this->assertIsObject( $callback_object );
		$this->assertSame( $this, $callback_object );
		$callback_function = $callback[1];
		$this->assertIsString( $callback_function );
		$this->assertSame( 'testNoAnnotations', $callback_function );

		/** @var Action $filter */
		$filter   = $filtered[1];
		$callback = $filter->getCallback();
		$this->assertIsArray( $callback );
		$callback_object = $callback[0];
		$this->assertIsObject( $callback_object );
		$this->assertSame( $this, $callback_object );
		$callback_function = $callback[1];
		$this->assertIsString( $callback_function );
		$this->assertSame( 'testGetAnnotations', $callback_function );
	}
}