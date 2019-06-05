<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Tests\Hooks\Model;

use DI\DependencyException;
use WpHookAnnotations\Hooks\Model\Action;
use InvalidArgumentException;
use WpHookAnnotations\Tests\ContainerTestCase;

/**
 * Class ActionTest
 *
 * All of the hook types (Action, Filter, Shortcode) are just marker types
 * that extend abstract Hook, so we just need to test one, right?
 *
 * @package WpHookAnnotations\Tests\Hooks\Model
 */
class ActionTest extends ContainerTestCase {

	public function testInvalidParams(): void {
		$this->expectException( InvalidArgumentException::class );
		$values = [ 'tag' => '' ];
		new Action( $values );
	}

	public function test__construct(): void {
		$values = [ 'tag' => 'admin_init' ];
		$action = new Action( $values );
		$this->assertSame( 10, $action->getPriority() );
		$this->assertSame( 1, $action->getAcceptedArgs() );
	}

	public function testSetAndGetCallback(): void {
		$values = [ 'tag' => 'admin_init' ];
		$action = new Action( $values );
		$action->setCallback(static function(){return 'test';});
		$this->assertSame('test',( $action->getCallback() )());
	}
}
