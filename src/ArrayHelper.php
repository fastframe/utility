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
	 * Returns whether the array is associative
	 *
	 * @see {http://stackoverflow.com/a/5969617/1281788}
	 */
	public static function isAssoc(array &$ary): bool
	{
		// @formatter:off
		for (reset($ary); is_int(key($ary)); next($ary));
		// @formatter:on

		return !is_null(key($ary));
	}

	/**
	 * Returns whether the array is a hash.
	 *
	 * Hashes are:
	 * - arrays with a mix of integer & string keys
	 * - arrays with non-sequential integer keys
	 */
	public static function isHash(array &$ary): bool
	{
		return (array_keys($keys = array_keys($ary))) !== $keys;
	}

	/**
	 * Returns the value of the key, or the alt if it doesn't exist
	 */
	public static function keyValue(array &$ary, string $key, mixed $alt = null): mixed
	{
		return array_key_exists($key, $ary) ? $ary[$key] : $alt;
	}

	/**
	 * Access a method on a list of objects
	 */
	public static function methodPull(array &$objects, ?string $method, ?string $keyMethod = null): array
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
	 */
	public static function pluck(array &$ary, string $key, ?string $keyId = null): array
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
	 */
	public static function propertyPull(array &$objects, ?string $property, ?string $keyProperty = null): array
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
	public static function pullIndex(array &$ary, array|string $idxs): array
	{
		return self::pullKeys($ary, $idxs);
	}

	/**
	 * Pulls the values from the array with a list of keys
	 */
	public static function pullKeys(array &$ary, array|string $keys): array
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
	 */
	public static function pullPrefix(array &$ary, string $prefix, bool $stripPrefix = false): array
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
	 */
	public static function pushPrefix(array &$ary, string $prefix): array
	{
		$values = [];
		foreach (array_keys($ary) as $key) {
			$values[$prefix . $key] = $ary[$key];
		}

		return $values;
	}

	/**
	 * Determines if just the first argument should be returned.
	 */
	public static function resolveSplat(array $args): mixed
	{
		return count($args) === 1
			? $args[0]
			: $args;
	}

	/**
	 * Return a string of "key: value;" pairs
	 *
	 * NOTE: this currently only works on simple arrays
	 */
	public static function toComment(
		array  &$ary,
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