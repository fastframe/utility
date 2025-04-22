<?php

/**
 * @file
 * Contains \FastFrame\Utility\NestedArrayHelper
 */

namespace FastFrame\Utility;

/**
 * Utility functions for dealing with nested arrays
 *
 * This uses dotted notation (some.where.over.the.rainbow)
 *
 * @package FastFrame\Utility
 */
class NestedArrayHelper
{
	const DEFAULT_SEPARATOR = '.';

	/**
	 * The separator to split strings
	 */
	protected static string $separator = self::DEFAULT_SEPARATOR;

	/**
	 * Changes the separator used in {convertToArray()}
	 */
	public static function setSeparator(string $separator = self::DEFAULT_SEPARATOR): void
	{
		static::$separator = $separator;
	}

	/**
	 * Returns the value at the given path
	 *
	 * Returns the default value if not specified
	 */
	public static function &get(array &$ary, array|string $key, mixed $alt = null): mixed
	{
		$key   = self::convertToArray($key);
		$ref   =& $ary;
		$found = false;
		while (($node = array_shift($key)) !== null) {
			if (is_array($ref) && array_key_exists($node, $ref)) {
				$ref   =& $ref[$node];
				$found = true;
				continue;
			}

			$found = false;
			break;
		}

		// Can't single line this due to the ref return
		if ($found) {
			return $ref;
		}

		return $alt;
	}

	/**
	 * Sets the value at the given path
	 */
	public static function set(array &$ary, array|string $key, mixed $value): void
	{
		$key = self::convertToArray($key);
		while (($node = array_shift($key)) !== null) {
			if (is_array($ary) && array_key_exists($node, $ary)) {
				if (!empty($key)) {
					$ary =& $ary[$node];
					continue;
				}
			}
			elseif (!empty($key)) {
				$ary[$node] = [];
				$ary        =& $ary[$node];
				continue;
			}

			$ary[$node] = $value;
		}
	}

	/**
	 * Returns whether the array has the given key
	 *
	 * If you are going to use the value it's better to use NestedArray::get() instead of this function
	 */
	public static function has(array &$ary, array|string $key): bool
	{
		$key = self::convertToArray($key);
		while (($node = array_shift($key)) !== null) {
			if (is_array($ary) && array_key_exists($node, $ary)) {
				$ary =& $ary[$node];
				continue;
			}

			return false;
		}

		return true;
	}

	/**
	 * Merges arrays better than array_merge_recursive
	 *
	 * The first array becomes the base for comparison on merge.
	 */
	public static function deepMerge(array ...$arys): mixed
	{
		$prime = array_shift($arys);

		while ($ary = array_shift($arys)) {
			$copy = array_diff_key($ary, $prime);
			foreach ($ary as $key => $value) {
				if (!array_key_exists($key, $copy) && (is_array($prime[$key]) || is_array($value))) {
					$prime[$key] = self::deepMerge((array)$prime[$key], (array)$value);
					continue;
				}
				$prime[$key] = $value;
			}
		}

		return $prime;
	}

	/**
	 * Expands the array from dotted notation
	 */
	public static function expand(array &$ary): array
	{
		$newAry = [];
		foreach ($ary as $key => $value) {
			self::set($newAry, $key, $value);
		}

		return $newAry;
	}

	/**
	 * Compresses the array into dotted notation
	 */
	public static function compress(array &$ary): array
	{
		$newAry = [];
		foreach ($ary as $k1 => $v1) {
			if (is_array($v1)) {
				foreach (self::compress($v1) as $k2 => $v2) {
					$newAry["$k1.$k2"] = $v2;
				}
				continue;
			}

			self::set($newAry, $k1, $v1);
		}

		return $newAry;
	}

	/**
	 * Plucks the requested value from the array into an array
	 */
	public static function pluck(array &$ary, string $value, ?string $key = null): array
	{
		$values = [];
		foreach ($ary as $itemKey => $item) {
			if (static::has($item, $value) &&
				($key === null || ($itemKey = static::get($item, $key)) !== null)
			) {
				$values[$itemKey] = static::get($item, $value);
			}
		}

		return $values;
	}

	/**
	 * Converts the given nodes into an array if needed
	 */
	protected static function convertToArray(array|string $key): array
	{
		return is_array($key) ? $key : explode(static::$separator, $key);
	}
}