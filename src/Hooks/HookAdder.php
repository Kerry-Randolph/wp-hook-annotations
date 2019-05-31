<?php
declare( strict_types=1 );

namespace HookAnnotations\Hooks;

use DI\DependencyException;
use DI\NotFoundException;
use HookAnnotations\Hooks\Model\Action;
use HookAnnotations\Hooks\Model\Filter;
use HookAnnotations\Hooks\Model\Hook;
use HookAnnotations\Hooks\Model\Shortcode;

/**
 * Class HookAdder
 *
 * Reads an object for hook related annotations, and adds callbacks wordpress
 *
 * @package HookAnnotations\Hooks
 */
class HookAdder {

	/**
	 * @param Hook[] $hookbacks
	 *
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function addHooks( array $hookbacks ): void {
		foreach ( $hookbacks as $hookback ) {
			if ( $hookback instanceof Action ) {
				add_action( $hookback->getTag(),
					$hookback->getCallback(),
					$hookback->getPriority(),
					$hookback->getAcceptedArgs() );
			} elseif ( $hookback instanceof Shortcode ) {
				add_shortcode( $hookback->getTag(),
					$hookback->getCallback() );
			} elseif ( $hookback instanceof Filter ) {
				add_filter( $hookback->getTag(),
					$hookback->getCallback(),
					$hookback->getPriority(),
					$hookback->getAcceptedArgs() );
			}
		}
	}
}