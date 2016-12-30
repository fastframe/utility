<?php

/**
 * @file
 * Contains \FastFrame\Utility\ArrayHelperTest
 */

namespace FastFrame\Utility;

/**
 * Tests for the ArrayHelper class
 *
 * @package FastFrame\Utility
 */
class ArrayHelperTest
	extends \PHPUnit_Framework_TestCase
{
	private $simpleAry = [
		'woot' => 'kakaw'
	];

	private $pullArray = [
		'some.where.over.the.rainbow' => 'blue birds fly',
		'some.where.over.the.wagon'   => 'wheel',
		'some.where.else'             => 'something booms',
		'boom.to'                     => 'square'
	];

	private $pluckPayload =[
		['username' => 'flastname', 'name' => 'Firstname Lastname'],
		['username' => 'fastframe', 'name' => 'FastFrame'],
	];

	public function testIsAssocReturnsTrue()
	{
		$ary = ['super' => 'duper'];
		self::assertTrue(ArrayHelper::isAssoc($ary));
	}

	public function testIsAssocReturnsFalse()
	{
		$ary = ['super', 'duper'];
		self::assertFalse(ArrayHelper::isAssoc($ary));
	}

	public function testIsAssocReturnsFalseWithExplicitIntegerKeys()
	{
		$ary = [2 => 'a', 4 => 'b'];
		self::assertFalse(ArrayHelper::isAssoc($ary));
	}

	public function testIsAssocReturnsTrueWithMixedKeys()
	{
		$ary = ['super', 'duper', 'woot' => 'kakaw'];
		self::assertTrue(ArrayHelper::isAssoc($ary));
	}

	public function testKeyValueReturnsValue()
	{
		self::assertEquals('kakaw', ArrayHelper::keyValue($this->simpleAry, 'woot'));
	}

	public function testKeyValueReturnsDefault()
	{
		self::assertEquals('world', ArrayHelper::keyValue($this->simpleAry, 'hello', 'world'));
	}

	public function testPullPrefixWithoutStrippingPrefix()
	{
		$e = [
			'some.where.over.the.rainbow' => 'blue birds fly',
			'some.where.over.the.wagon'   => 'wheel',
			'some.where.else'             => 'something booms',
		];
		self::assertEquals($e, ArrayHelper::pullPrefix($this->pullArray, 'some.where'));
	}

	public function testPullPrefixWithStrippingPrefix()
	{
		$e = [
			'over.the.rainbow' => 'blue birds fly',
			'over.the.wagon'   => 'wheel',
			'else'             => 'something booms',
		];
		self::assertEquals($e, ArrayHelper::pullPrefix($this->pullArray, 'some.where.', true));
	}

	public function testPushPrefix()
	{
		$ary = [
			'over.the.rainbow' => 'blue birds fly',
			'over.the.wagon'   => 'wheel',
			'else'             => 'something booms',
		];
		$e = [
			'some.where.over.the.rainbow' => 'blue birds fly',
			'some.where.over.the.wagon'   => 'wheel',
			'some.where.else'             => 'something booms',
		];
		self::assertEquals($e, ArrayHelper::pushPrefix($ary, 'some.where.'));
	}

	public function testPluck()
	{
		$e = [
			'Firstname Lastname',
			"FastFrame"
		];
		self::assertEquals($e, ArrayHelper::pluck($this->pluckPayload, "name"));
	}

	public function testPluckWithKeyFromItem()
	{
		$e = [
			'flastname' => 'Firstname Lastname',
			'fastframe' => "FastFrame"
		];
		self::assertEquals($e, ArrayHelper::pluck($this->pluckPayload, "name", 'username'));
	}
}