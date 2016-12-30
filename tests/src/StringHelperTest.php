<?php

/**
 * @file
 * Contains \FastFrame\Utility\StringHelperTest
 */

namespace FastFrame\Utility;

/**
 * Tests for the StringHelper class
 *
 * @package FastFrame\Utility
 */
class StringHelperTest
	extends \PHPUnit_Framework_TestCase
{
	public function provideValidContains()
	{
		return [
			['world', 'Hello, world!'],
			[['world'], 'Hello, world!'],
			[['my', 'boy'], 'Hello boy, this is my world!']
		];
	}
	public function provideNeedlesForException()
	{
		return [
			[new \stdClass()],
			[[new \stdClass()]],
			[[['hi']]]
		];
	}

	/**
	 * @dataProvider provideValidContains
	 */
	public function testContainsReturnsTrue($needle, $haystack)
	{
		self::assertTrue(StringHelper::contains($needle, $haystack));
	}

	public function testContainsReturnsFalse()
	{
		self::assertFalse(StringHelper::contains('super', 'whacko'));
	}

	public function testContainsReturnsFalseWithEmptyInput()
	{
		self::assertFalse(StringHelper::contains(null, ''));
	}

	/**
	 * @dataProvider provideNeedlesForException
	 */
	public function testContainsThrowsInvalidArgumentWithBadNeedle($payload)
	{
		$this->setExpectedException("InvalidArgumentException");
		StringHelper::contains($payload, 'woot');
	}

	public function testFirstPositionWithString()
	{
		self::assertEquals(7, StringHelper::firstPosition('world', 'Hello, world!'));
	}

	public function testFirstPositionWithSingleItemArray()
	{
		self::assertEquals(7, StringHelper::firstPosition(['world'], 'Hello, world!'));
	}

	public function testFirstPositionWithMultiItemArray()
	{
		self::assertEquals(6, StringHelper::firstPosition(['my', 'boy'], 'Hello boy, this is my world!'));
	}

	public function testFirstPositionReturnsFalseWithEmptyInput()
	{
		self::assertFalse(StringHelper::firstPosition(null, ''));
	}


	/**
	 * @dataProvider provideNeedlesForException
	 */
	public function testFirstPositionThrowsInvalidArgumentWithBadNeedle($payload)
	{
		$this->setExpectedException("InvalidArgumentException");
		StringHelper::firstPosition($payload, 'woot');
	}
}