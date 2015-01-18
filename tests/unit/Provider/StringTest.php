<?php
/**
 * PHP Version 5.5
 * @category Library
 * @package ChangeLog
 * @author Steve "uru" West <steve.david.west@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @link https://github.com/stevewest/changelog
 */

namespace ChangeLog\Provider;

use Codeception\TestCase\Test;

/**
 * Tests for Provider\String
 */
class StringTest extends Test
{

	public function testGetSetContent()
	{
		$content = ['foo'];
		$string = new String($content);

		$this->assertEquals(
			$content,
			$string->getContent()
		);

		$string = new String;
		$string->setContent("baz\nbat");
		$this->assertEquals(
			['baz', 'bat'],
			$string->getContent()
		);
	}

}
