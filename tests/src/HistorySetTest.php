<?php

/**
 * @file
 * Contains \FastFrame\Utility\HistorySetTest
 */

namespace FastFrame\Utility;

use PHPUnit\Framework\TestCase;

/**
 * Tests of the HistorySet
 *
 * @package FastFrame\Utility
 */
class HistorySetTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->set = new HistorySet([1, 2, 3]);
	}

	public function testAdd()
	{
		$this->set->add(4);

		self::assertEquals([4], iterator_to_array($this->set->added()));
		self::assertEquals([1,2,3,4], $this->set->values());
	}

	public function testRemove()
	{
		$this->set->remove(3);

		self::assertEquals([3], iterator_to_array($this->set->removed()));
		self::assertEquals([1,2], $this->set->values());
	}

	public function xtestRemoveThrowsKeyError()
	{}

	public function testReset()
	{
		$this->set->add(4);
		$this->set->remove(2);
		$this->set->reset();

		self::assertEmpty(iterator_to_array($this->set->added()));
		self::assertEmpty(iterator_to_array($this->set->removed()));
		self::assertEquals([1,3,4], $this->set->values());
	}

	public function testResetOnlyAdded()
	{
		$this->set->add(4);
		$this->set->remove(2);
		$this->set->reset(added: true);

		self::assertEmpty(iterator_to_array($this->set->added()));
		self::assertEquals([2], iterator_to_array($this->set->removed()));
		self::assertEquals([1,3,4], $this->set->values());
	}

	public function testResetOnlyRemoved()
	{
		$this->set->add(4);
		$this->set->remove(2);
		$this->set->reset(removed: true);

		self::assertEmpty(iterator_to_array($this->set->removed()));
		self::assertEquals([4], iterator_to_array($this->set->added()));
		self::assertEquals([1,3,4], $this->set->values());
	}

	public function xtestAddOnlyRetainsOne()
	{
		$set = new Set([]);
		$set->add([]);

		self::assertEquals([[]], $set->values());
	}

	public function testClear()
	{
		$this->set->clear();

		self::assertEquals([1,2,3], iterator_to_array($this->set->removed()));
		self::assertEquals([], $this->set->values());
	}

	public function testClearThenAdd()
	{
		$this->set->clear();
		$this->set->add(3);

		self::assertEmpty(iterator_to_array($this->set->added()));
		self::assertEquals([1,2], iterator_to_array($this->set->removed()));
		self::assertEquals([3], $this->set->values());
	}

	public function testNonEidetic()
	{
		$set = new HistorySet([1,2,3], eidetic: true);
		$set->add(4);
		$set->remove(4);

		self::assertEquals([4], iterator_to_array($set->added()));
		self::assertEquals([4], iterator_to_array($set->removed()));
	}

	public function testAtomic()
	{
		$this->set->add(4);

		self::assertEquals([4], iterator_to_array($this->set->added()));
		self::assertEmpty(iterator_to_array($this->set->removed()));

		$this->set->remove(4);

		self::assertEmpty(iterator_to_array($this->set->added()));
		self::assertEmpty(iterator_to_array($this->set->removed()));
	}
}