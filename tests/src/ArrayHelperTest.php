<?php

/**
 * @file
 * Contains \FastFrame\Utility\ArrayHelperTest
 */

namespace FastFrame\Utility;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the ArrayHelper class
 *
 * @package FastFrame\Utility
 */
class ArrayHelperTest
	extends TestCase
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

	private $pluckPayload = [
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
		$e   = [
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

	public function testPluckWithEmptyItems()
	{
		$e = [
			'Firstname Lastname',
			"FastFrame"
		];

		$payload   = $this->pluckPayload;
		$payload[] = ['username' => 'empty-item'];
		self::assertEquals($e, ArrayHelper::pluck($payload, "name"));
	}

	public function testPluckWithEmptyItemsOrKey()
	{
		$e = [
			'flastname' => 'Firstname Lastname',
			'fastframe' => "FastFrame",
		];

		$payload   = $this->pluckPayload;
		$payload[] = ['username' => 'empty-item'];
		$payload[] = ['name' => 'Empty Item'];
		self::assertEquals($e, ArrayHelper::pluck($payload, "name", "username"));
	}

	public function testIsHash()
	{
		$ary = [1 => true, 3 => true];
		self::assertTrue(ArrayHelper::isHash($ary));
	}

	public function testIndexPull()
	{
		$ary = [
			['k1' => 1],
			['k3' => 3],
			['k4' => 4],
			['k1' => 5]
		];
		self::assertEquals(
			[
				['k1' => 1],
				[],
				[],
				['k1' => 5]
			],
			ArrayHelper::indexPull($ary, ['k1', 'k5'])
		);
	}

	public function testPropertyPull()
	{
		$ary = [
			(object)['id' => 1],
			(object)['id' => 3],
			(object)['id' => 5],
		];
		self::assertEquals(
			[
				1,
				3,
				5
			],
			ArrayHelper::propertyPull($ary, 'id')
		);
	}

	public function testPropertyPullReturnsObjects()
	{
		$ary = [
			(object)['id' => 1],
			(object)['id' => 3],
			(object)['id' => 5],
		];
		self::assertEquals(
			$ary,
			ArrayHelper::propertyPull($ary, null)
		);
	}

	public function testPropertyPullReturnsKeyProperty()
	{
		$ary = [
			(object)['id' => 1, 'key' => 'a'],
			(object)['id' => 3, 'key' => 'b'],
			(object)['id' => 5, 'key' => 'c'],
		];
		self::assertEquals(
			[
				'a' => 1,
				'b' => 3,
				'c' => 5
			],
			ArrayHelper::propertyPull($ary, 'id', 'key')
		);
	}

	public function testPropertyPullReturnsKeyPropertyWithObjects()
	{
		$ary = [
			$a = (object)['id' => 1, 'key' => 'a'],
			$b = (object)['id' => 3, 'key' => 'b'],
			$c = (object)['id' => 5, 'key' => 'c'],
		];
		self::assertEquals(
			[
				'a' => $a,
				'b' => $b,
				'c' => $c
			],
			ArrayHelper::propertyPull($ary, null, 'key')
		);
	}

	public function testMethodPull()
	{
		$ary = [
			new class {
				public function callme()
				{
					return 'maybe';
				}
			},
			new class {
				public function callme()
				{
					return 'never';
				}
			}
		];
		self::assertEquals(
			['maybe', 'never'],
			ArrayHelper::methodPull($ary, 'callme')
		);
	}

	public function testMethodPullReturnsObjects()
	{
		$ary = [
			new \stdClass(),
			new \stdClass(),
			new \stdClass(),
		];
		self::assertEquals(
			$ary,
			ArrayHelper::methodPull($ary, null)
		);
	}

	public function testMethodPullReturnsKeyedMethod()
	{
		$ary = [
			new class {
				public function callme()
				{
					return 'maybe';
				}
			},
			new class {
				public function callme()
				{
					return 'never';
				}
			}
		];
		self::assertEquals(
			['maybe' => 'maybe', 'never' => 'never'],
			ArrayHelper::methodPull($ary, 'callme', 'callme')
		);
	}

	public function testMethodPullReturnsKeyedMethodWithObjects()
	{
		$ary = [
			$a = new class {
				public function callme()
				{
					return 'maybe';
				}
			},
			$b = new class {
				public function callme()
				{
					return 'never';
				}
			}
		];
		self::assertEquals(
			['maybe' => $a, 'never' => $b],
			ArrayHelper::methodPull($ary, null, 'callme')
		);
	}

	public function testResolveSplatReturnsfull()
	{
		self::assertEquals(
			['test', 'test', 'test'],
			ArrayHelper::resolveSplat(['test', 'test', 'test'])
		);
	}

	public function testResolveSplatReturnsFirst()
	{
		self::assertEquals(
			['test', 'test', 'test'],
			ArrayHelper::resolveSplat([['test', 'test', 'test']])
		);
	}

	public function testResolveSplatReturnsString()
	{
		self::assertEquals(
			'test',
			ArrayHelper::resolveSplat(['test'])
		);
	}


	public function testToCommentDefaults()
	{
		$ary = [
			'test'  => 'kakaw',
			'kakaw' => 'test'
		];
		self::assertEquals(
			"test=kakaw; kakaw=test",
			ArrayHelper::toComment($ary)
		);
	}

	public function testToCommentAcceptsCustomSeparators()
	{
		$ary = [
			'test'  => 'kakaw',
			'kakaw' => 'test'
		];
		self::assertEquals(
			"test: kakaw|\nkakaw: test",
			ArrayHelper::toComment($ary, ": ", "|\n")
		);
	}
}