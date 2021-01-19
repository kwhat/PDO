<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL\Test\Clause;

use FaaPz\PDO\MySQL;
use PHPUnit\Framework\TestCase;

class LimitTest extends TestCase
{
    public function testToStringWithOffset()
    {
        $subject = new MySQL\Clause\Limit(10, 25);

        $this->assertEquals('LIMIT ?, ?', $subject->__toString());
    }

    public function testToStringWithoutOffset()
    {
        $subject = new MySQL\Clause\Limit(10);

        $this->assertEquals('LIMIT ?', $subject->__toString());
    }

    public function testGetValuesWithOffset()
    {
        $subject = new MySQL\Clause\Limit(10, 25);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }

    public function testGetValuesWithoutOffset()
    {
        $subject = new MySQL\Clause\Limit(10);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(1, $subject->getValues());
    }
}
