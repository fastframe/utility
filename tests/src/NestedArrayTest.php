<?php

/**
 * @file
 * Contains \FastFrame\Utility\NestedArrayHelperTest
 */

namespace FastFrame\Utility;

/**
 * Tests for the NestedArrayHelper class
 *
 * @package FastFrame\Utility
 */
class NestedArrayHelperTest
	extends \PHPUnit_Framework_TestCase
{
	private $aryTester = [
		'some' => [
			'where' => [
				'over' => [
					'the' => [
						'rainbow' => 'blue birds fly'
					]
				]
			]
		]
	];

	private $pluckPayload = [
		[
			'username' => 'flastname',
			'name'     => 'Firstname Lastname',
			'test'     => ['sub' => 'first last', 'name' => 'kakaw']
		],
		['username' => 'fastframe', 'name' => 'FastFrame', 'test' => ['sub' => 'fast frame', 'name' => 'woot']],
	];

	public function providePluckCases()
	{
		$cases =  [
			[
				$this->pluckPayload,
				'name',
				null,
				[
					'Firstname Lastname',
					"FastFrame"
				]
			],
			[
				$this->pluckPayload,
				'test.sub',
				null,
				[
					'first last',
					"fast frame"
				]
			],
			[
				$this->pluckPayload,
				'name',
				'username',
				[
					'flastname' => 'Firstname Lastname',
					'fastframe' => "FastFrame"
				]
			],
			[
				$this->pluckPayload,
				'test.name',
				'test.sub',
				[
					'first last' => 'kakaw',
					'fast frame' => "woot"
				]
			],
			[
				$this->pluckPayload,
				'username',
				'test.name',
				[
					'kakaw' => 'flastname',
					'woot'  => "fastframe"
				]
			]
		];

		// missing item value
		$payload = $this->pluckPayload;
		$payload[] = ['username' => 'missing-item-value-for-name'];
		$cases[] = [
			$payload,
			'name',
			null,
			[
				'Firstname Lastname',
				"FastFrame"
			]
		];

		// missing sub-level value
		$cases[] = [
			$payload,
			'test.sub',
			null,
			[
				'first last',
				"fast frame"
			]
		];

		// missing item key
		$payload = $this->pluckPayload;
		$payload[] = ['name' => 'missing-item-value-for-name'];
		$cases[] = [
			$payload,
			'test.name',
			'test.sub',
			[
				'first last' => 'kakaw',
				'fast frame' => "woot"
			]
		];

		return $cases;
	}

	public function testSeparatorChanges()
	{
		NestedArrayHelper::setSeparator('/');
		$s = NestedArrayHelper::get($this->aryTester, 'some/where/over/the/rainbow');
		self::assertEquals('blue birds fly', $s);
		NestedArrayHelper::setSeparator(); // reset it... maybe we should find another way to handle this?
	}

	public function testGetReturnsWithStringPath()
	{
		$s = NestedArrayHelper::get($this->aryTester, 'some.where.over.the.rainbow');
		self::assertEquals('blue birds fly', $s);
	}

	public function testGetReturnsWithArrayPath()
	{
		self::assertEquals(
			'blue birds fly', NestedArrayHelper::get(
			$this->aryTester, [
			'some',
			'where',
			'over',
			'the',
			'rainbow'
		]));
	}

	public function testGetReturnsDefaultWhenPathNotFound()
	{
		$ary = [];
		$a   = NestedArrayHelper::get($ary, ['some', 'where', 'over', 'the', 'rainbow'], 'uh oh');
		self::assertEquals('uh oh', $a);
	}

	public function testSetWithStringPath()
	{
		$ary = [];
		NestedArrayHelper::set($ary, 'some.where.over.the.rainbow', 'blue birds fly');
		self::assertEquals($this->aryTester, $ary);
	}

	public function testSetWithArrayPath()
	{
		$ary = [];
		NestedArrayHelper::set($ary, ['some', 'where', 'over', 'the', 'rainbow'], 'blue birds fly');
		self::assertEquals($this->aryTester, $ary);
	}

	public function testSetDoesNotClobberExistingKeys()
	{
		$ary                                                 = $this->aryTester;
		$ary['some']['where']['over']['the']['wagon']        = 'wheel';
		$expected                                            = $ary;
		$expected['some']['where']['over']['the']['rainbow'] = 'blue birds fly high';

		NestedArrayHelper::set($ary, ['some', 'where', 'over', 'the', 'rainbow'], 'blue birds fly high');
		self::assertEquals($expected, $ary);
	}

	public function testHasReturnsFalse()
	{
		$ary = [];

		self::assertFalse(NestedArrayHelper::has($ary, 'superman.is'));
	}

	public function testHasReturnTrue()
	{
		self::assertTrue(NestedArrayHelper::has($this->aryTester, ['some', 'where', 'over']));
	}

	public function testDeepMergeMergesWithTwoArray()
	{
		$a1 = [
			'super' => [
				'duper' => [
					'woot' => 'kakaw'
				]
			],
			'some'  => [
				'where' => [
					'over' => [
						'the' => [
							'wagon' => 'wheel'
						]
					]
				]
			]
		];

		$e                                            = $a1;
		$e['some']['where']['over']['the']['rainbow'] = 'blue birds fly';

		self::assertEquals($e, NestedArrayHelper::deepMerge($a1, $this->aryTester));
	}

	public function testDeepMergeMergesWithMultipleArray()
	{
		$a1 = [
			'super' => [
				'duper' => [
					'woot' => 'kakaw'
				]
			],
			'some'  => [
				'where' => [
					'over' => [
						'the' => [
							'wagon' => 'wheel'
						]
					]
				]
			]
		];
		$a2 = [
			'boom' => [
				'to' => 'square'
			]
		];

		$e                                            = $a1;
		$e['some']['where']['over']['the']['rainbow'] = 'blue birds fly';
		$e['boom']['to']                              = 'square';

		self::assertEquals($e, NestedArrayHelper::deepMerge($a1, $this->aryTester, $a2));
	}

	public function testDeepMergeWithNonHash()
	{
		$a1 = [
			[['poi']],
			[['test']],
			3 => 'poi',
		];
		$a2 = [
			[['event']],
			2 => 'kakaw',
			3 => 'woot',
		];
		$e = [
			[['event']],
			[['test']],
			'kakaw',
			'woot'
		];

		self::assertEquals($e, NestedArrayHelper::deepMerge($a1, $a2));
	}

	public function testExpand()
	{
		$ary = [
			'some.where.over.the.rainbow' => 'blue birds fly',
			'some.where.over.the.wagon'   => 'wheel',
			'some.where.else'             => 'something booms',
			'boom.to'                     => 'square'
		];
		$e   = [
			'some' => [
				'where' => [
					'else' => 'something booms',
					'over' => [
						'the' => [
							'wagon'   => 'wheel',
							'rainbow' => 'blue birds fly'
						]
					]
				]
			],
			'boom' => [
				'to' => 'square'
			]
		];
		self::assertEquals($e, NestedArrayHelper::expand($ary));
	}

	public function testCompress()
	{
		$e   = [
			'some.where.over.the.rainbow' => 'blue birds fly',
			'some.where.over.the.wagon'   => 'wheel',
			'some.where.else'             => 'something booms',
			'boom.to'                     => 'square'
		];
		$ary = [
			'some' => [
				'where' => [
					'else' => 'something booms',
					'over' => [
						'the' => [
							'wagon'   => 'wheel',
							'rainbow' => 'blue birds fly'
						]
					]
				]
			],
			'boom' => [
				'to' => 'square'
			]
		];
		self::assertEquals($e, NestedArrayHelper::compress($ary));
	}

	/**
	 * @dataProvider providePluckCases
	 */
	public function testPluck($payload, $value, $key, $expected)
	{
		self::assertEquals($expected, NestedArrayHelper::pluck($payload, $value, $key));
	}

}