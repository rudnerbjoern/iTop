<?php

namespace Combodo\iTop\Test\UnitTest;

/**
 * Shows that
 * 1) the option runClassInSeparateProcess is equivalent to runTestsInSeparateProcesses
 * 2) setUpBeforeClass is called within each spawned process (the main one, then in eventuel subprocesses)
 * 3) setUp behaves as expected, i.e. called one within the same process as the test itself
 *
 * @preserveGlobalState disabled
 * @runClassInSeparateProcess
 */
class runClassInSeparateProcessTest extends ItopDataTestCase
{
	static public function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass(); // TODO: Change the autogenerated stub

		file_put_contents(
			dirname(__FILE__).'/pid.txt',
			getmypid().';'.static::class.';'.__METHOD__."\n",
			FILE_APPEND);
	}

	protected function LogPid()
	{
		file_put_contents(
			dirname(__FILE__).'/pid.txt',
			getmypid().';'.static::class.';'.$this->getName()."\n",
			FILE_APPEND);
	}

	function testA()
	{
		$this->LogPid();
		static::assertTrue(true);
	}

	function testB()
	{
		$this->LogPid();
		static::assertTrue(true);
	}

	/**
	 * @dataProvider CProvider
	 */
	function testC($i)
	{
		$this->LogPid();
		static::assertTrue(true);
	}

	function CProvider()
	{
		return [
			[1],
			[1],
			[1],
			[1],
		];
	}
}
