<?php
declare( strict_types=1 );

namespace HookAnnotations\Annotations;

use InvalidArgumentException;

trait ConstructionHelper {

	protected function indexExistsAndValueNotEmptyString(
		string $index,
		array $values
	): bool {

		if ( ! array_key_exists( $index, $values ) ) {
			return false;
		}

		$value = $values[ $index ];

		if ( ! is_string( $value ) ) {
			return false;
		}

		if ( empty( $value ) ) {
			return false;
		}

		return true;
	}

	protected function indexExistsAndValueNotEmptyStringGuard(
		string $index,
		array $values
	): string {

		if ( ! $this->indexExistsAndValueNotEmptyString( $index, $values ) ) {
			throw new InvalidArgumentException( "$index index missing or value not string or empty string" );
		}

		return $values[ $index ];
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