<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Reflections;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;

class ReflectionFactory {

	/**
	 * Takes an object and returns an array of Reflector objects for the class,
	 * its methods, and properties
	 *
	 * Only returns reflections for the child class, no parent
	 *
	 * @param object $source_object
	 *
	 * @return Reflector[]
	 * @throws ReflectionException
	 */
	public function getAllReflectors( object $source_object ): array {

		$reflectors = [];
		$reflectors = array_merge(
			$reflectors,
			$this->getMethodReflectors( $source_object )
		);
		$reflectors = array_merge(
			$reflectors,
			$this->getPropertyReflectors( $source_object )
		);
		// add the class reflector
		$reflectors[] = new ReflectionClass( $source_object );

		return $reflectors;
	}

	/**
	 * Returns array of method Reflector objects
	 *
	 * Only returns reflections for the child class, no parent
	 *
	 * @param object $source_object
	 *
	 * @return Reflector[]
	 * @throws ReflectionException
	 */
	public function getMethodReflectors( object $source_object ): array {

		$class      = get_class( $source_object );
		$reflectors = [];

		$class_reflector = new ReflectionClass( $source_object );

		$method_reflectors = $class_reflector->getMethods();
		/** @var ReflectionMethod $method_reflector */
		foreach ( $method_reflectors as $property_reflector ) {
			if ( $property_reflector->class !== $class ) {
				continue; // ignore any inherited methods
			}

			$reflectors[] = $property_reflector;
		}

		return $reflectors;
	}

	/**
	 * Returns an array of property Reflector objects
	 *
	 * Only returns reflections for the child class, no parent
	 *
	 * @param object $source_object
	 *
	 * @return Reflector[]
	 * @throws ReflectionException
	 */
	public function getPropertyReflectors( object $source_object ): array {

		$class      = get_class( $source_object );
		$reflectors = [];

		$class_reflector = new ReflectionClass( $source_object );

		$property_reflectors = $class_reflector->getProperties();
		/** @var ReflectionProperty $property_reflector */
		foreach ( $property_reflectors as $property_reflector ) {
			if ( $property_reflector->class !== $class ) {
				continue; // ignore any inherited properties
			}

			$reflectors[] = $property_reflector;
		}

		return $reflectors;
	}
}