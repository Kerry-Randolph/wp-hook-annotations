<?php

namespace WpHookAnnotations\Tests\Hooks;

use DI\Annotation\Inject;
use DI\DependencyException;
use DI\NotFoundException;
use WpHookAnnotations\Hooks\HookAdder;
use WpHookAnnotations\Hooks\Model\Action;
use WpHookAnnotations\Hooks\Model\Filter;
use WpHookAnnotations\Hooks\Model\Shortcode;
use PHPUnit\Framework\TestCase;
use WpHookAnnotations\Tests\ContainerTestCase;

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
