<?php
declare( strict_types=1 );

namespace Tests\Hooks;

use DI\Annotation\Inject;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\Common\Annotations\AnnotationException;
use HookAnnotations\Hooks\HookManager;
use HookAnnotations\Hooks\Model\Action;
use HookAnnotations\Hooks\Model\Filter;
use HookAnnotations\Hooks\Model\Shortcode;
use ReflectionException;
use Tests\ContainerTestCase;

class HookManagerTest extends ContainerTestCase {

	/**
	 * @var HookManager $hook_manager
	 * @Inject()
	 */
	private $hook_manager;

	/**
	 * @Action(tag="test_action")
	 * @Filter(tag="test_filter")
	 * @Shortcode(tag="test_shortcode")
	 *
	 * @throws AnnotationException
	 * @throws ReflectionException
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function testProcessHooks(): void {
		$this->hook_manager->processHooks( $this );
		// check logs to verify
		// TODO: Figure out how to test with actual Wordpress hooks
		$this->assertTrue(true);
	}
}
