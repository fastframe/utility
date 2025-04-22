<?php

/**
 * @file
 * Contains \FastFrame\Utility\Set
 */

namespace FastFrame\Utility;

use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Set data structure
 *
 * This uses a hash of the serialization of the element to use as a key, since PHP
 *
 * Sort order is not guaranteed
 */
class Set
	implements IteratorAggregate, Countable
{
	protected array $data = [];

	protected string $hash = 'md5';

	public function __construct(mixed ...$elements)
	{
		foreach ($elements as $element) {
			$this->add($element);
		}
	}

	/**
	 * Adds the element to the set
	 */
	public function add(mixed $element): self
	{
		if ($this->keyExists($key = $this->resolveKey($element))) {
			return $this;
		}

		$this->data[$key] = $element;

		return $this;
	}

	/**
	 * Removes all elements from the set
	 */
	public function clear(): self
	{
		$this->data = [];

		return $this;
	}

	/**
	 * Whether the element exists in the set
	 */
	public function contains(mixed $element): bool
	{
		return $this->keyExists($this->resolveKey($element));
	}

	/**
	 * Number of elements in the set
	 */
	public function count(): int
	{
		return count($this->data);
	}

	/**
	 * Removes the element from the set if present
	 */
	public function discard(mixed $element): self
	{
		try {
			return $this->remove($element);
		}
		finally {
			return $this;
		}
	}

	public function getIterator(): Traversable
	{
		foreach ($this->data as $element) {
			yield $element;
		}
	}

	/**
	 * Whether the set is empty
	 */
	public function isEmpty(): bool
	{
		return empty($this->data);
	}

	/**
	 * Removes the element from the set if present
	 *
	 * @throws Exception\KeyError if the element is not in the set
	 */
	public function remove(mixed $element): self
	{
		if ($this->keyExists($key = $this->resolveKey($element))) {
			unset($this->data[$key]);

			return $this;
		}

		throw new Exception\KeyError("Element does not exist");
	}

	/**
	 * The values in the set
	 */
	public function values(): array
	{
		return array_values($this->data);
	}

	protected function keyExists(string $key): bool
	{
		return array_key_exists($key, $this->data);
	}

	protected function resolveKey(mixed $element): string
	{
		return hash($this->hash, serialize($element));
	}
}