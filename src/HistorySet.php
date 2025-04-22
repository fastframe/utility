<?php

/**
 * @file
 * Contains \FastFrame\Utility\HistorySet
 */

namespace FastFrame\Utility;

use Generator;

/**
 * Set implementation that tracks added/removed elements
 *
 * This is a port of the Python [history-set](https://pypi.org/project/history-set/) concept
 *
 * @package FastFrame\Utility
 */
class HistorySet
	extends Set
{
	/**
	 * List of added elements
	 */
	protected array $added   = [];

	/**
	 * List of removed elements
	 */
	protected array $removed = [];

	/**
	 * HistorySet constructor
	 */
	public function __construct(array $elements = [], protected bool $eidetic = false)
	{
		foreach ($elements as $element) {
			// we are seeding the data here so we don't want to call $this->add
			parent::add($element);
		}
	}

	/**
	 * Adds the element to the set, and records whether it was added
	 */
	public function add(mixed $element): self
	{
		if (!$this->keyExists($key = $this->resolveKey($element))) {
			if (!$this->eidetic && array_key_exists($key, $this->removed)) {
				unset($this->removed[$key]);
			}
			else {
				$this->added[$key] = $element;
			}
			$this->data[$key] = $element;
		}

		return $this;
	}

	/**
	 * List of added elements
	 */
	public function added(): Generator
	{
		foreach ($this->added as $key => $element) {
			yield $element;
		}
	}

	/**
	 * Removes all elements from the set, and marks them as removed
	 */
	public function clear(): self
	{
		foreach ($this->data as $key => $element) {
			if (!array_key_exists($key, $this->removed)) {
				$this->removed[$key] = $element;
			}
		}

		parent::clear();

		return $this;
	}

	/**
	 * Removes the element from the set
	 *
	 * @throws KeyError
	 */
	public function remove(mixed $element): self
	{
		if (!$this->keyExists($key = $this->resolveKey($element))) {
			throw new Exception\KeyError("element does not xist");
		}

		if (!$this->eidetic && array_key_exists($key, $this->added)) {
			unset($this->added[$key]);
		}
		else {
			$this->removed[$key] = $element;
		}

		unset($this->data[$key]);

		return $this;
	}

	/**
	 * List of removed elements
	 */
	public function removed(): Generator
	{
		foreach ($this->removed as $key => $element) {
			yield $element;
		}
	}

	/**
	 * Clears the history. Use `added: true` or `removed: true` to only clear that specific history
	 */
	public function reset(bool $added = false, bool $removed = false): self
	{
		$removed ^ true && $this->added = [];
		$added ^ true && $this->removed = [];

		return $this;
	}
}