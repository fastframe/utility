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
	 * @alias keysPull
	 */
	public static function indexPull(array &$arys, array $keys): array
	{
		return self::keysPull($arys, $keys);
	}

	/**
	 * Returns a list of values based on the passed in keys
	 */
	public static function keysPull(array &$arys, array $keys): array
	{
		$values = [];
		foreach (array_keys($arys) as $idx) {
			$values[$idx] = self::pullKeys($arys[$idx], $keys);
		}

		return $values;
	}

	/**
	 * Returns whether or not the array is associative
	 *
	 * @see {http://stackoverflow.com/a/5969617/1281788}
	 *
	 * @param array $ary The array to test
	 *
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
	 * Returns whether or not the array is a hash.
	 *
	 * Hashes are:
	 * - arrays with a mix of integer & string keys
	 * - arrays with non-sequential integer keys
	 *
	 * @param array $ary The array to test
	 *
	 * @return bool
	 */
	public static function isHash(array &$ary)
	{
		return (array_keys($keys = array_keys($ary))) !== $keys;
	}

	/**
	 * Returns the value of the key, or the alt if it doesn't exist
	 *
	 * @param array      $ary The array to get the value by key from
	 * @param string     $key The key to get
	 * @param null|mixed $alt The alternate value if the key doesn't exist in the array
	 *
	 * @return mixed
	 */
	public static function keyValue(array &$ary, $key, $alt = null)
	{
		return array_key_exists($key, $ary) ? $ary[$key] : $alt;
	}

	/**
	 * Access a method on a list of objects
	 *
	 * @param array       $objects    The list of objects to run the method on
	 * @param string|null $method     The method to call on the objects
	 * @param string|null $keyMethod  The method to call for the key. Default is the key in the array
	 *
	 * @return array
	 */
	public static function methodPull(array &$objects, ?string $method, string $keyMethod = null): array
	{
		$values = [];
		foreach ($objects as $key => $object) {
			$key          = $keyMethod ? $object->$keyMethod() : $key;
			$values[$key] = $method ? $object->$method() : $object;
		}

		return $values;
	}

	/**
	 * Plucks the requested key from the arrays into an array
	 *
	 * @param array       $ary   The array to iterate over
	 * @param string      $key   The key to get from the array
	 * @param string|null $keyId The key to use as the ID
	 *
	 * @return array
	 */
	public static function pluck(array &$ary, $key, $keyId = null)
	{
		$values = [];
		foreach ($ary as $itemKey => $item) {
			if (array_key_exists($key, $item) &&
				($keyId === null || ($itemKey = (array_key_exists($keyId, $item) ? $item[$keyId] : null)) !== null)
			) {
				$values[$itemKey] = $item[$key];
			}
		}

		return $values;
	}

	/**
	 * Access a property on a list of objects
	 *
	 * @param array       $objects     The list of objects to get the property for
	 * @param string|null $property    The object property
	 * @param string|null $keyProperty The object property to use as the key
	 *
	 * @return array
	 */
	public static function propertyPull(array &$objects, ?string $property, string $keyProperty = null): array
	{
		$values = [];
		foreach ($objects as $key => $object) {
			$key          = $keyProperty ? $object->$keyProperty : $key;
			$values[$key] = $property ? $object->$property : $object;
		}

		return $values;
	}

	/**
	 * @alias pullKeys
	 */
	public static function pullIndex(array &$ary, $idxs): array
	{
		return self::pullKeys($ary, $idxs);
	}

	/**
	 * Pulls the values from the array with a list of keys
	 *
	 * @param array $ary  The array to loop over
	 * @param mixed $keys Either an array of indexes, or a single index
	 *
	 * @return array
	 */
	public static function pullKeys(array &$ary, $keys): array
	{
		$values = [];
		foreach ((array)$keys as $key) {
			if (array_key_exists($key, $ary)) {
				$values[$key] = $ary[$key];
			}
		}

		return $values;
	}

	/**
	 * Pulls the values from the array with a key given a prefix
	 *
	 * Optionally strips the prefix from the keys
	 *
	 * @param array  $ary         The array to iterate over
	 * @param string $prefix      The key prefix
	 * @param bool   $stripPrefix Whether or not to strip the prefix from the keys
	 *
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
	 * @param array  $ary    The array to iterate over
	 * @param string $prefix The prefix to add to the keys
	 *
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
	 * Determines if just the first argument should be returned.
	 *
	 * @param array $args
	 *
	 * @return mixed The splat, or first argument if only one
	 */
	public static function resolveSplat(array $args)
	{
		return count($args) === 1
			? $args[0]
			: $args;
	}

	/**
	 * Return a string of "key: value;" pairs
	 *
	 * NOTE: this currently only works on simple arrays
	 *
	 * @param array  $ary            The array to operate on
	 * @param string $valueSeparator The separator between key and value
	 * @param string $pairSeparator  The separator between the pairs
	 * @return string
	 */
	public static function toComment(array &$ary,
									 string $valueSeparator = "=",
									 string $pairSeparator = "; "
	): string {
		$result = '';
		foreach ($ary as $key => $value) {
			$result .= "{$key}{$valueSeparator}{$value}{$pairSeparator}";
		}

		return trim($result, $pairSeparator);
	}
}