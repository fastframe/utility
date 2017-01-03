<?php

/**
 * @file
 * Contains \FastFrame\Utility\ArrayHelper
 */

namespace FastFrame\Utility;

/**
 * Utility class for working with basic PHP arrays
 *
 * @package FastFrame\Utility
 */
class ArrayHelper
{
	/**
	 * Returns whether or not the array is associative
	 *
	 * @see {http://stackoverflow.com/a/5969617/1281788}
	 * @param array $ary
	 * @return bool
	 */
	public static function isAssoc(array &$ary)
	{
		// @formatter:off
		for (reset($ary); is_int(key($ary)); next($ary));
		// @formatter:on

		return is_null(key($ary)) ? false : true;
	}

	/**
	 * Returns the value of the key, or the alt if it doesn't exist
	 *
	 * @param array      $ary
	 * @param string     $key
	 * @param null|mixed $alt
	 * @return mixed
	 */
	public static function keyValue(array &$ary, $key, $alt = null)
	{
		return array_key_exists($key, $ary) ? $ary[$key] : $alt;
	}

	/**
	 * Pulls the values from the array with a key given a prefix
	 *
	 * Optionally strips the prefix from the keys
	 *
	 * @param array  $ary
	 * @param string $prefix
	 * @param bool   $stripPrefix
	 * @return array
	 */
	public static function pullPrefix(array &$ary, $prefix, $stripPrefix = false)
	{
		$values = [];
		foreach (array_keys($ary) as $key) {
			if (stripos($key, $prefix) === 0) {
				$values[$stripPrefix ? str_ireplace($prefix, '', $key) : $key] = $ary[$key];
			}
		}

		return $values;
	}

	/**
	 * Pushes the values into an array with a key given a prefix
	 *
	 * This is the reverse of array_pull_prefix in that it will set the key to the "{prefix}{key}"
	 *
	 * @param array  $ary
	 * @param string $prefix
	 * @return array
	 */
	public static function pushPrefix(array &$ary, $prefix)
	{
		$values = [];
		foreach (array_keys($ary) as $key) {
			$values[$prefix . $key] = $ary[$key];
		}

		return $values;
	}

	/**
	 * Plucks the requested value from the array into an array
	 *
	 * @param array       $ary
	 * @param string      $value
	 * @param string|null $key
	 * @return array
	 */
	public static function pluck(array &$ary, $value, $key = null)
	{
		$values = [];
		foreach ($ary as $itemKey => $item) {
			if (array_key_exists($value, $item) &&
				($key === null || ($itemKey = (array_key_exists($key, $item) ? $item[$key] : null)) !== null)
			) {
				$values[$itemKey] = $item[$value];
			}
		}

		return $values;
	}
}