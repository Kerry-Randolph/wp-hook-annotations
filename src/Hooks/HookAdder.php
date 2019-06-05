<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Hooks;

use DI\DependencyException;
use DI\NotFoundException;
use WpHookAnnotations\Hooks\Model\Action;
use WpHookAnnotations\Hooks\Model\Filter;
use WpHookAnnotations\Hooks\Model\Hook;
use WpHookAnnotations\Hooks\Model\Shortcode;

/**
 * Class HookAdder
 *
 * Reads an object for hook related annotations, and adds callbacks wordpress
 *
 * @package WpHookAnnotations\Hooks
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
			/* Order is important. Action extends (is_a) filter, so we need to
			   check instanceof Action first */
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