<?php

namespace Tests\Hooks;

use DI\Annotation\Inject;
use DI\DependencyException;
use DI\NotFoundException;
use HookAnnotations\Hooks\HookAdder;
use HookAnnotations\Hooks\Model\Action;
use HookAnnotations\Hooks\Model\Filter;
use HookAnnotations\Hooks\Model\Shortcode;
use PHPUnit\Framework\TestCase;
use Tests\ContainerTestCase;

class HookAdderTest extends ContainerTestCase {

	/**
	 * @var HookAdder $hook_adder
	 * @Inject()
	 */
	private $hook_adder;

	/**
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function testAddHookbacks(): void {
		$filter = new Filter( [ 'tag' => 'hatd_filter_tester' ] );
		$filter->setCallback( static function ( ) {
			return 'the brown cow';
		} );
		$this->hook_adder->addHooks( [ $filter ] );

		$action = new Action( [
			'tag'           => 'hatd_action_tester',
			'priority'      => 10,
			'accepted_args' => 1
		] );
		$this->hook_adder->addHooks( [ $action ] );

		$sc_callback = static function () {
			return 'it worked';
		};
		$shortcode   = new Shortcode( [ 'tag' => 'hatd_test_shortcode' ] );
		$shortcode->setCallback($sc_callback);
		$this->hook_adder->addHooks( [ $shortcode ] );

		$this->assertTrue(true);
		// check log
		// TODO: Figure out how to test against actual wordpress hook code
	}
}
