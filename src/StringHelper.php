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
	 * Returns whether or not any of the needles exist in the haystack
	 *
	 * @param string|array $needles
	 * @param string       $haystack
	 * @return bool|int
	 */
	public static function contains($needles, $haystack)
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
	 * @param string|array $needles
	 * @param string       $haystack
	 * @return bool|int|null
	 */
	public static function firstPosition($needles, $haystack)
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
	 * Returns whether or not the var passed in is a string
	 *
	 * @param $str
	 */
	protected static function assertString($str)
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
	 * @param $needles
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	protected static function convertNeedle($needles)
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