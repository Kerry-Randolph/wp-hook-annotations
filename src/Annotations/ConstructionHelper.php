<?php
declare( strict_types=1 );

namespace HookAnnotations\Annotations;

use InvalidArgumentException;

trait ConstructionHelper {

	/**
	 * Gets the value in the array associated with $index
	 * If index doesn't exist, or value not a string, returns empty string
	 *
	 * @param string $index
	 * @param array  $values
	 *
	 * @return string
	 */
	protected function getValue(
		string $index,
		array $values
	): string {

		if ( ! array_key_exists( $index, $values ) ) {
			return '';
		}

		$value = $values[ $index ];

		if ( ! is_string( $value ) ) {
			return '';
		}

		return $value;
	}

	protected function trySet(
		string $index,
		array $values,
		callable $test_scalar,
		callable $setter
	): void {
		if ( array_key_exists( $index, $values ) ) {
			$value = $values[ $index ];
			if ( $test_scalar( $value ) ) {
				$setter( $value );
			} else {
				throw new InvalidArgumentException( "$index value not "
				                                    . $test_scalar );
			}
		}
	}
}