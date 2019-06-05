<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Hooks\Model;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 *
 */
class Filter extends Hook {

	/**
	 * @var int
	 */
	private $priority = 10;
	/**
	 * @var int
	 */
	private $accepted_args = 1;

	/**
	 * Hook constructor.
	 *
	 * @param array $values
	 *
	 */
	public function __construct( array $values ) {
		parent::__construct( $values );

		$this->setPriority( 10 );
		$this->trySet( 'priority', $values, 'is_int',
			[ $this, 'setPriority' ] );

		$this->setAcceptedArgs( 1 );
		$this->trySet( 'accepted_args', $values, 'is_int',
			[ $this, 'setAcceptedArgs' ] );

		// set default empty callback
		$this->setCallback( static function () {
		} );
	}

	/**
	 * @return int
	 */
	public function getPriority(): int {
		return $this->priority;
	}

	/**
	 * @param int $priority
	 */
	public function setPriority( int $priority ): void {
		$this->priority = $priority;
	}

	/**
	 * @return int
	 */
	public function getAcceptedArgs(): int {
		return $this->accepted_args;
	}

	/**
	 * @param int $accepted_args
	 */
	public function setAcceptedArgs( int $accepted_args ): void {
		$this->accepted_args = $accepted_args;
	}
}