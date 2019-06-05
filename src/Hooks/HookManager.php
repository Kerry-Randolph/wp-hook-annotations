<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Hooks;

use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\Common\Annotations\AnnotationException;
use WpHookAnnotations\Annotations\AnnotationFactory;
use WpHookAnnotations\Hooks\Model\Hook;
use ReflectionException;
use ReflectionMethod;
use Reflector;

class HookManager {

	/**
	 * @var AnnotationFactory
	 */
	private $annotation_factory;
	/**
	 * @var HookAdder
	 */
	private $hook_adder;

	/**
	 * HookManager constructor.
	 *
	 * @param HookAdder         $hook_adder
	 * @param AnnotationFactory $annotation_factory
	 */
	public function __construct(
		HookAdder $hook_adder,
		AnnotationFactory $annotation_factory
	) {
		$this->hook_adder         = $hook_adder;
		$this->annotation_factory = $annotation_factory;
	}

	/**
	 * @param object $source_object
	 *
	 * @throws DependencyException
	 * @throws NotFoundException
	 * @throws AnnotationException
	 * @throws ReflectionException
	 */
	public function processHooks( object $source_object ): void {

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

		$hookbacks = $this->annotation_factory->makeAnnotationObjects(
			$source_object,
			$closure,
			Hook::class
		);

		$this->hook_adder->addHooks( $hookbacks );
	}
}