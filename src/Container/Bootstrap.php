<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Container;

use DI\Container;
use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Exception;

class Bootstrap {

	private static $container;

	/**
	 * @return mixed
	 * @throws Exception
	 */
	public static function getContainer()
	{
		if (null === static::$container) {
			static::$container = static::init();
		}
		return static::$container;
	}

	/**
	 * @return Container
	 * @throws Exception
	 */
	private static function init(): Container {
		require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';
		//require_once '../../vendor/autoload.php';

		/* This is just a workaround for now.
		 * at some point there won't be an AnnotationRegistry
		 * https://github.com/doctrine/annotations/issues/182
		 * Then this won't be needed at all
		 */
		// Changed Annotationfactory to do this
		// Cause bootstrap never called if library, right?
		//AnnotationRegistry::registerLoader( 'class_exists' );

		$builder = new ContainerBuilder;
		$builder->useAnnotations( true );
		$builder->addDefinitions( __DIR__ . '/config.php' );
		$container = $builder->build();

		return $container;
	}
}