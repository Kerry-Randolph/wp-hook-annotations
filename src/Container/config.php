<?php
declare( strict_types=1 );

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use function DI\string;

return [

	'base.path'      => static function () {
		return dirname( __DIR__, 2 );
	},
	'log.name'       => 'hook-annotations-debug',
	'test.log.name'  => 'hook-annotations-test',
	'log.path'       => string( '{base.path}/logs/{log.name}.log' ),
	'test.log.path'  => string( '{base.path}/logs/{test.log.name}.log' ),
	'log.level'      => static function ( ) {
		return Logger::DEBUG;
	},
	'test.log.level' => static function ( ) {
		return Logger::INFO;
	},

	'test.logger'          => static function ( ContainerInterface $c ) {
		$logger      = new Logger( $c->get( 'test.log.name' ) );
		$fileHandler = new StreamHandler( $c->get( 'test.log.path' ),
			$c->get( 'test.log.level' ) );
		$fileHandler->setFormatter( new LineFormatter() );
		$logger->pushHandler( $fileHandler );

		return $logger;
	},

	// Used the PSR-3 interface for injections. That way we can replace Monolog with any PSR-3 logger anytime we want
	LoggerInterface::class => static function ( ContainerInterface $c ) {
		$logger      = new Logger( $c->get( 'log.name' ) );
		$fileHandler = new StreamHandler( $c->get( 'log.path' ),
			$c->get( 'log.level' ) );
		$fileHandler->setFormatter( new LineFormatter() );
		$logger->pushHandler( $fileHandler );

		return $logger;
	},

	Cache::class => static function ( ContainerInterface $c ) {
		$array_cache = new ArrayCache();
		$file_cache  = new FilesystemCache(
			$c->get( 'base.path' ) . '/cache'
		);
		$chain_cache = new ChainCache( [ $array_cache, $file_cache ] );
		$chain_cache->setNamespace( 'hook-annotations_' );

		return $chain_cache;
	},

	Reader::class => static function ( ) {
		// Caching disabled in dev/stg
		return new AnnotationReader();

		// enable in prod
		/*
		$cache = $c->get(Cache::class);
		return new CachedReader(
			new AnnotationReader(),
			$cache,
			$debug = false // dev/stg -> true, prod -> false
		);
		*/
	},
];