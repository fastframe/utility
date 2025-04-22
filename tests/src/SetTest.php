<?php

/**
 * @file
 * Contains \FastFrame\Utility\SetTest
 */

namespace FastFrame\Utility;

use FastFrame\Utility\Exception\KeyError;
use PHPUnit\Framework\TestCase;

/**
 * Tests of the Set
 *
 * @package FastFrame\Utility
 */
class SetTest
	extends TestCase
{
	public function testAdd()
	{
		$set = new Set();

		self::assertSame($set, $set->add([]));
		self::assertEquals([[]], $set->values());
	}

	public function testAddOnlyRetainsOne()
	{
		$set = new Set([]);
		$set->add([]);

		self::assertEquals([[]], $set->values());
	}

	public function testClear()
	{
		$set = new Set([]);

		self::assertSame($set, $set->clear());
		self::assertEquals([], $set->values());
	}

	public function testContainsReturnsTrue()
	{
		self::assertTrue((new Set(['k', 'a']))->contains(['k', 'a']));
	}

	public function testContainsReturnsFalse()
	{
		self::assertFalse((new Set(['a']))->contains(['k', 'a']));
	}

	public function testCount()
	{
		self::assertCount(1, new Set([]));
	}

	public function testGetIterator()
	{
		$actual = [];
		foreach ((new Set(['k'], ['a'])) as $value) {
			$actual[] = $value;
		}

		self::assertEquals([['k'], ['a']], $actual);
	}

	public function testIsEmptyReturnsTrue()
	{
		self::assertTrue((new Set)->isEmpty());
	}

	public function testIsEmptyReturnsFalse()
	{
		self::assertFalse((new Set([]))->isEmpty());
	}

	public function testRemove()
	{
		$set = new Set(['k'], ['a']);

		self::assertSame($set, $set->remove(['a']));
		self::assertEquals([['k']], $set->values());
	}

	public function testRemoveThrowsKeyError()
	{
		self::expectException(KeyError::class);
		(new Set())->remove('test');
	}

	public function testDiscard()
	{
		$set = (new Set('test'))->discard('test');
		self::assertEmpty($set->values());
	}

	public function testDiscardIgnoresKeyError()
	{
		$set = (new Set(1))->discard('test');

		self::assertEquals([1], $set->values());
	}

	public function testVariousTypes()
	{
		$set = new Set([1, 2, 3]);
		$set->add(4);
		$set->discard('kakaw');
		$set->add($s1 = new Set);
		$set->add(new Set); // same as $s1
		$set->add($s3 = new Set([]));

		self::assertEquals(
			[[1, 2, 3], 4, $s1, $s3],
			$set->values()
		);
	}
}