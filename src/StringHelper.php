<?php

/**
 * @file
 * Contains \FastFrame\Utility\StringHelper
 */

namespace FastFrame\Utility;

/**
 * Utility class for working with strings
 *
 * @package FastFrame\Utility
 */
class StringHelper
{
	/**
	 * Returns whether any of the needles exist in the haystack
	 */
	public static function contains(array|string|null $needles, string $haystack): bool
	{
		if (empty($haystack) || empty($needles)) {
			return false;
		}

		foreach (self::convertNeedle($needles) as &$needle) {
			self::assertString($needle);
			if (stripos($haystack, $needle) !== false) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Find position of first occurrence of a group of strings
	 *
	 * @see {http://codereview.stackexchange.com/a/52309}
	 */
	public static function firstPosition(array|string|null $needles, string $haystack): bool|int|null
	{
		if (empty($haystack) || empty($needles)) {
			return false;
		}

		$max = $min = strlen($haystack) + 1;
		foreach (self::convertNeedle($needles) as &$needle) {
			self::assertString($needle);
			$pos = stripos($haystack, $needle);
			if ($pos !== false && $pos < $min) {
				$min = $pos;
			}
		}

		return ($min === $max) ? null : $min;
	}

	/**
	 * Returns whether the var passed in is a string
	 */
	protected static function assertString(mixed $str): void
	{
		if (!is_string($str)) {
			throw new \InvalidArgumentException("Must be a string");
		}
	}

	/**
	 * Converts to an array if it is string
	 *
	 * If not an array then this throws an \InvalidArgumentException
	 *
	 * @throws \InvalidArgumentException
	 */
	protected static function convertNeedle(array|string $needles): array
	{
		if (is_string($needles)) {
			$needles = [$needles];
		}
		elseif (!is_array($needles)) {
			throw new \InvalidArgumentException("\$needles must be an array or string");
		}

		return $needles;
	}
}