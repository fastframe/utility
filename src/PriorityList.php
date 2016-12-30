<?php

/**
 * @file
 * Contains \FastFrame\Utility\PriorityList
 */
namespace FastFrame\Utility;

/**
 * Implements a Priority List.
 *
 * This is based on a priority queue but doesn't delete the items on iteration.
 *
 * @package FastFrame\Utility
 */
class PriorityList
	implements \Iterator, \Countable
{
	const EXTR_PRIORITY = \SplPriorityQueue::EXTR_PRIORITY;
	const EXTR_DATA = \SplPriorityQueue::EXTR_DATA;
	const EXTR_BOTH = \SplPriorityQueue::EXTR_BOTH;

	/**
	 * Flags for returning data via current()
	 *
	 * @var int
	 */
	private $flags;

	/**
	 * The node index in the priority
	 *
	 * @var int|bool
	 */
	private $node = false;

	/**
	 * The priority index
	 *
	 * @var int|bool
	 */
	private $priorityIndex = -1;

	/**
	 * The priority
	 *
	 * @var int
	 */
	private $priority;

	/**
	 * Number of items in the list
	 *
	 * @var int
	 */
	private $count = 0;

	/**
	 * List of the nodes
	 *
	 * @var array
	 */
	private $nodes = [];

	/**
	 * List of the priorities
	 *
	 * @var array
	 */
	private $priorities = [];

	/**
	 * PriorityList constructor.
	 *
	 * @param int $flags The flags for current
	 */
	public function __construct($flags = self::EXTR_DATA)
	{
		$this->flags = $flags;
	}

	/**
	 * Inserts the value into the queue
	 *
	 * @param mixed $value
	 * @param int   $priority
	 */
	public function insert($value, $priority = 0)
	{
		if (!is_int($priority)) {
			throw new \InvalidArgumentException("Priority argument must be an integer");
		}

		if (!isset($this->nodes[$priority])) {
			$this->nodes[$priority] = [];
			$this->resetPriorities($this->priorityIndex);
		}

		$this->nodes[$priority][] = $value;
		$this->count++;
	}

	/**
	 * Removes the node from the queue
	 *
	 * If $priority is not specified then it finds the first matching item
	 *
	 * @param  mixed    $value
	 * @param  int|null $priority
	 * @return bool True when the item was removed, false otherwiser
	 */
	public function remove($value, $priority = null)
	{
		foreach ($this->priorities as $nodePriority) {
			if ($priority == null || $nodePriority === $priority) {
				foreach ($this->nodes[$nodePriority] as $key => $node) {
					if ($node === $value) {
						$this->count--;
						unset($this->nodes[$nodePriority][$key]);
						if (empty($this->nodes[$nodePriority])) {
							unset($this->nodes[$nodePriority]);
							$this->resetPriorities($this->priorityIndex);
						}

						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Returns a count of the number of items in the queue
	 *
	 * @param int|null $priority The priority to count. Default is all
	 * @return int
	 */
	public function count($priority = null)
	{
		return $priority === null ? $this->count : count($this->nodes[$priority]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function current()
	{
		if ($this->flags === self::EXTR_DATA) {
			return $this->nodes[$this->priority][$this->node];
		}
		elseif ($this->flags === self::EXTR_PRIORITY) {
			return $this->priority;
		}

		return [
			'data'     => $this->nodes[$this->priority][$this->node],
			'priority' => $this->priority
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function next()
	{
		$this->node = $this->findNext($this->nodes[$this->priority], $this->node);
		if ($this->node === false) {
			$this->priorityIndex = $this->findNext($this->priorities, $this->priorityIndex);
			if ($this->priorityIndex === false) {
				// this is the end of the list, call rewind() to restart
				$this->priorityIndex = -1;
				$this->priority      = null;
			}
			else {
				$this->priority = $this->priorities[$this->priorityIndex];
				$this->node     = $this->findNext($this->nodes[$this->priority], -1);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function valid()
	{
		if (false === $this->node || -1 === $this->priorityIndex || false === $this->priority) {
			return false;
		}

		return isset($this->nodes[$this->priority][$this->node]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function key()
	{
		return $this->priority . '-' . $this->node;
	}

	/**
	 * {@inheritdoc}
	 */
	public function rewind()
	{
		$this->resetPriorities(-1);
		$this->node = $this->findNext($this->nodes[$this->priority], -1);
	}

	/**
	 * Reloads the priorities and sets the priorityIndex
	 *
	 * Used during insert/remove of values from the list
	 *
	 * @param int|null $priorityIndex
	 */
	private function resetPriorities($priorityIndex = null)
	{
		$this->priorities = array_keys($this->nodes);
		rsort($this->priorities, SORT_NUMERIC | SORT_NATURAL);

		$this->priorityIndex = $priorityIndex >= 0
			? $priorityIndex
			: $this->findNext($this->priorities, -1);
		$this->priority      = $this->priorities[$this->priorityIndex];
	}

	/**
	 * Returns the next integer index in the array
	 *
	 * @param $ary
	 * @param $idx
	 * @return bool|int
	 */
	private function findNext(&$ary, $idx)
	{
		$count = count($ary);
		for ($idx += 1; $idx <= $count; ++$idx) {
			if (array_key_exists($idx, $ary)) {
				return $idx;
			}
		}

		return false;
	}
}