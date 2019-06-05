<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Annotations;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;
use WpHookAnnotations\Reflections\ReflectionFactory;

class AnnotationFactory {

	/**
	 * @var Reader
	 */
	private $annotation_reader;
	/**
	 * @var ReflectionFactory
	 */
	private $reflection_factory;

	/**
	 * AnnotationObjectFactory constructor.
	 *
	 * @param ReflectionFactory $reflection_factory
	 *
	 */
	public function __construct(
		ReflectionFactory $reflection_factory
	) {
		$this->annotation_reader  = $this->getAnnotationReader();
		$this->reflection_factory = $reflection_factory;
	}

	/**
	 * Returns an array of custom annotation objects created from the source object
	 *
	 * An exception is thrown if invalid annotation encountered. Tries to ignore
	 * and continue, but throws exception on 10th try
	 *
	 * If a callback is passed, it is run and passed the annotation object
	 * with its associated reflector before being added to the array
	 * Why?
	 * For one example, this lets you add the source method name to the hook object
	 *
	 * @param object      $source_object
	 *
	 * @param callable    $callback
	 *
	 * @param string|null $class_filter
	 *
	 * @return object[]
	 * @throws AnnotationException
	 * @throws ReflectionException
	 */
	public function makeAnnotationObjects(
		object $source_object,
		callable $callback = null,
		string $class_filter = null
	): array {

		if ( isset( $class_filter ) && ! class_exists( $class_filter ) ) {
			throw new InvalidArgumentException
			( $class_filter . ' class not found' );
		}

		$method_reflectors
			= $this->reflection_factory->getMethodReflectors( $source_object );

		if ( empty( $method_reflectors ) ) {
			return [];
		}

		$annotation_objects = [];

		foreach ( $method_reflectors as $reflector ) {
			$objects = [];

			$loop_count = 1;
			while ( true ) {

				//$this->logger->debug(print_r($reflector, true));

				try {
					$objects = $this->tryGetAnnotations( $reflector );
					//$this->logger->debug(print_r($objects, true));
					break; // success!
				} catch ( AnnotationException $e ) {

					$message = $e->getMessage();
					// $this->logger->warning( $message );

					//TODO: this feels janky, but the Doctrine annotations lib
					//      doesn't provide a better way to ignore invalid
					//      annotations that you don't know beforehand
					if ( preg_match_all( '`"@([^"]*)"`', $message,
						$matches )
					) {
						$name = $matches[1][0];
						// $this->getLogger()->info( 'ignoring: ' . $name );
						// Ignore the annotation that cause the exception
						// Not sure if both these are needed?
						AnnotationReader::addGlobalIgnoredName( $name );
						AnnotationReader::addGlobalIgnoredNamespace( $name );
					}

					// On the 10th fail, barf
					if ( $loop_count ++ === 10 ) {
						throw new AnnotationException( $message );
					}
				}
			}

			foreach ( $objects as $object ) {

				if ( isset( $class_filter )
				) {
					if ( ! $object instanceof $class_filter ) {
						continue;
					}
				}

				if ( $callback ) {
					$callback( $object, $reflector );
				}

				$annotation_objects[] = $object;
			}
		}

		return $annotation_objects;
	}

	/**
	 * Will throw AnnotationException if invalid annotation found
	 *
	 * @param Reflector $reflector
	 *
	 * @return object[]
	 */
	private function tryGetAnnotations( Reflector $reflector ): array {
		$objects = [];

		if ( $reflector instanceof ReflectionClass ) {
			$objects
				= $this->annotation_reader->getClassAnnotations( $reflector );
		}

		if ( $reflector instanceof ReflectionMethod ) {
			$objects
				= $this->annotation_reader->getMethodAnnotations( $reflector );
		}

		if ( $reflector instanceof ReflectionProperty ) {
			$objects
				= $this->annotation_reader->getPropertyAnnotations( $reflector );
		}

		return $objects;
	}

	private function getAnnotationReader(): Reader {
		AnnotationRegistry::registerLoader( 'class_exists' );
		$annotation_reader = new AnnotationReader();

		return $annotation_reader;
	}
}