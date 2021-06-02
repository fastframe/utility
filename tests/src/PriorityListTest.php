<?php

/**
 * @file
 * Contains \FastFrame\Utility\PriorityListTest
 */

namespace FastFrame\Utility;

use PHPUnit\Framework\TestCase;

/**
 * Tests of the PriorityList
 *
 * @package FastFrame\Event
 */
class PriorityListTest
	extends TestCase
{
	private $sampleCount0 = 2;
	private $sampleCount = 4;
	private $samplePayload = [
		['1', 0],
		['2', 2],
		[3, 1],
		['test', 0]
	];

	public function provideCurrentPayload()
	{
		return [
			[PriorityList::EXTR_DATA, ['2', 3, '1', 'test']],
			[PriorityList::EXTR_PRIORITY, [2, 1, 0, 0]],
			[
				PriorityList::EXTR_BOTH,
				[
					['data' => '2', 'priority' => 2],
					['data' => 3, 'priority' => 1],
					['data' => '1', 'priority' => 0],
					['data' => 'test', 'priority' => 0]
				]
			],
		];
	}

	public function provideValidInsertValues()
	{
		return [
			[1],
			['super'],
			[
				function () {
					return 'woot';
				}
			],
			[[$this, 'nothing']],
			[new \stdClass()]
		];
	}

	private function buildSampleList($flags = PriorityList::EXTR_DATA)
	{
		$pl = new PriorityList($flags);
		foreach ($this->samplePayload as $data) {
			$pl->insert($data[0], $data[1]);
		}

		return $pl;
	}

	private function fetchList(PriorityList $list)
	{
		$values = [];
		foreach ($list as $key => $value) {
			$values[] = $value;
		}

		return $values;
	}

	/**
	 * @dataProvider provideValidInsertValues
	 */
	public function testInsertWithValidValues($value)
	{
		$pl = new PriorityList();
		$pl->insert($value, 0);

		self::assertEquals($value, $pl->current());
	}

	public function testInsertThrowsExceptionOnNonIntegerPriority()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage("Priority argument must be an integer");
		(new PriorityList())->insert('a', 'bad');
	}

	public function testInsertWithNegativePriority()
	{
		$pl = new PriorityList();
		$pl->insert(1, 0);
		$pl->insert(2, -2);
		$pl->insert(3, 1);

		self::assertEquals([3, 1, 2], $this->fetchList($pl));
	}

	public function testCountReturnsZero()
	{
		self::assertEquals(0, (new PriorityList())->count());
	}

	public function testCountReturnsTotal()
	{
		self::assertEquals(count($this->samplePayload), $this->buildSampleList()->count());
	}


	public function testCountReturnsTotalForPriority()
	{
		self::assertEquals($this->sampleCount0, $this->buildSampleList()->count(0));
	}

	/**
	 * @dataProvider provideCurrentPayload
	 */
	public function testCurrent($type, $expected)
	{
		self::assertSame(
			$expected,
			$this->fetchList($this->buildSampleList($type))
		);
	}

	public function testRemove()
	{
		$pl = $this->buildSampleList();

		self::assertTrue($pl->remove('2'));
		self::assertEquals($this->sampleCount - 1, $pl->count());
		self::assertSame([3, '1', 'test'], $this->fetchList($pl));
	}

	public function testRemoveOnlyCatchesFirst()
	{
		$pl = $this->buildSampleList();
		$pl->insert('2', 2);

		self::assertTrue($pl->remove('2'));
		self::assertEquals(4, $pl->count());
		self::assertSame(['2', 3, '1', 'test'], $this->fetchList($pl));
	}

	public function testRemoveOnlyUsesPriorityWhenSet()
	{
		$pl = $this->buildSampleList();
		$pl->insert('test', 100);
		self::assertFalse($pl->remove('test', 50));
		self::assertSame(['test', '2', 3, '1', 'test'], $this->fetchList($pl));
	}

	public function testRemoveReturnsFalse()
	{
		$pl= $this->buildSampleList();

		self::assertFalse($pl->remove('tester'));

	}

	public function testRemoveReturnsFalseForPriority()
	{
		$pl= $this->buildSampleList();

		self::assertFalse($pl->remove('tester', 100));

	}
}