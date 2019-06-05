<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Hooks;

use DI\Annotation\Inject;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;

trait HookAware {

	/**
	 * @Inject()
	 * @param HookManager $hook_manager
	 *
	 * @throws AnnotationException
	 * @throws ReflectionException
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function processHooks( HookManager $hook_manager
	): void {
		$hook_manager->processHooks( $this );
	}
}