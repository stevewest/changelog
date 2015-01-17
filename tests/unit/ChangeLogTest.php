<?php
/**
 * PHP Version 5.5
 * @category Library
 * @package ChangeLog
 * @author Steve "uru" West <steve.david.west@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @link https://github.com/stevewest/changelog
 */

namespace ChangeLog;

use Codeception\TestCase\Test;

/**
 * Tests for ChangeLog
 */
class ChangeLogTest extends Test
{

	/**
	 * @var ChangeLog
	 */
	protected $changeLog;

	protected function _before()
	{
		$this->changeLog = new ChangeLog;
	}

}