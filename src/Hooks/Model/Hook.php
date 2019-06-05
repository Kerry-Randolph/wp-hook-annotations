<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Hooks\Model;

use Doctrine\Common\Annotations\Annotation\Required;
use WpHookAnnotations\Annotations\ConstructionHelper;
use InvalidArgumentException;

/**
 * Class Hook
 *
 * A tag and associated callback
 *
 * @package WpHookAnnotations\Hooks\Model
 */
abstract class Hook {
	use ConstructionHelper;

	/**
	 * @Required
	 * @var string
	 */
	private $tag;

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
		$value = $this->getValue( $index, $values );
		if ( ! $value ) {
			throw new InvalidArgumentException(
				"$index missing or value not non-empty string"
			);
		}
		$this->setTag( $value );

		// set default empty callback
		$this->setCallback( static function () {
		} );
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