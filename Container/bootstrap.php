<?php
declare( strict_types=1 );

/**
 * The bootstrap file creates and returns the container.
 */

use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$builder->useAnnotations( true );
$builder->addDefinitions( __DIR__ . '/config.php' );
$wp_hook_annotations_container = $builder->build();

/* This is just a workaround for now.
 * at some point there won't be an AnnotationRegistry
 * https://github.com/doctrine/annotations/issues/182
 * Then this won't be needed at all
 */
AnnotationRegistry::registerLoader( 'class_exists' );

return $wp_hook_annotations_container;