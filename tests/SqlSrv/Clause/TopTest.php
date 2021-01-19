<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\Test\SqlSrv\Clause;

use FaaPz\PDO\SqlSrv\Clause\Top;
use PHPUnit\Framework\TestCase;

class TopTest extends TestCase
{
    public function testToString()
    {
        $subject = new Top(10);

        $this->assertEquals('TOP ?', $subject->__toString());
    }

    public function testToStringWithPrecent()
    {
        $subject = new Top(10, true);

        $this->assertEquals('TOP ? PERCENT', $subject->__toString());
    }

    public function testGetValuesWithOffset()
    {
        $subject = new Top(10);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(1, $subject->getValues());
    }
}
