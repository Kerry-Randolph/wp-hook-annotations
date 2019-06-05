<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Annotations;

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

	/**
	 * Searches for $index in the array $values
	 * If found, tests the associated value with $test_scalar
	 * If test passed, executes the $setter callback
	 *
	 * @param string   $index
	 * @param array    $values
	 * @param callable $test_scalar
	 * @param callable $setter
	 */
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