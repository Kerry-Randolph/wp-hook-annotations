<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Tests\Hooks;

use DI\Annotation\Inject;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\Common\Annotations\AnnotationException;
use WpHookAnnotations\Hooks\HookManager;
use WpHookAnnotations\Hooks\Model\Action;
use WpHookAnnotations\Hooks\Model\Filter;
use WpHookAnnotations\Hooks\Model\Shortcode;
use ReflectionException;
use WpHookAnnotations\Tests\ContainerTestCase;

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
