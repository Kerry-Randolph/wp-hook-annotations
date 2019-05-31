<?php
declare( strict_types=1 );

namespace HookAnnotations\Hooks\Model;

use Doctrine\Common\Annotations\Annotation\Required;
use HookAnnotations\Annotations\ConstructionHelper;

abstract class Hook {
	use ConstructionHelper;

	/**
	 * @Required
	 * @var string
	 */
	private $tag;
	/**
	 * @var int
	 */
	private $priority = 10;
	/**
	 * @var int
	 */
	private $accepted_args = 1;

	/**
	 * @var callable
	 */
	private $callback;

	/**
	 * Hook constructor.
	 *
	 * @param array $values
	 *
	 */
	public function __construct( array $values ) {

		$index = 'tag';
		$value = $this->indexExistsAndValueNotEmptyStringGuard( $index,
			$values );
		$this->setTag( $value );

		$this->setPriority( 10 );
		$this->trySet( 'priority', $values, 'is_int',
			[ $this, 'setPriority' ] );

		$this->setAcceptedArgs( 1 );
		$this->trySet( 'accepted_args', $values, 'is_int',
			[ $this, 'setAcceptedArgs' ] );

		// set default empty callback
		$this->setCallback( static function () {} );
	}

	/**
	 * @return string
	 */
	public function getTag(): string {
		return $this->tag;
	}

	/**
	 * @param string $tag
	 */
	public function setTag( string $tag ): void {
		$this->tag = $tag;
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

	/**
	 * @return callable
	 */
	public function getCallback(): callable {
		return $this->callback;
	}

	/**
	 * @param callable $callback
	 */
	public function setCallback( callable $callback ): void {
		$this->callback = $callback;
	}
}