<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\Test\SqlSrv\Clause;

use FaaPz\PDO\MySQL;
use FaaPz\PDO\SqlSrv\Clause\Offset;
use PHPUnit\Framework\TestCase;

class OffsetTest extends TestCase
{
    public function testToStringWithOffset()
    {
        $subject = new Offset(10, 25);

        $this->assertEquals('OFFSET ? ROWS FETCH NEXT ? ROWS ONLY', $subject->__toString());
    }

    public function testToStringWithoutOffset()
    {
        $subject = new Offset(10);

        $this->assertEquals('OFFSET ?', $subject->__toString());
    }

    public function testGetValuesWithOffset()
    {
        $subject = new Offset(10, 25);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }

    public function testGetValuesWithoutOffset()
    {
        $subject = new Offset(10);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(1, $subject->getValues());
    }
}
