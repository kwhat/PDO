<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SqlSrv\Clause;

use FaaPz\PDO\QueryBuilder\SqlSrv;
use PHPUnit\Framework\TestCase;

class OffsetTest extends TestCase
{
    public function testToStringWithOffset()
    {
        $subject = new SqlSrv\Clause\Offset(10, 25);

        $this->assertEquals('OFFSET ? ROWS FETCH NEXT ? ROWS ONLY', $subject->__toString());
    }

    public function testToStringWithoutOffset()
    {
        $subject = new SqlSrv\Clause\Offset(10);

        $this->assertEquals('OFFSET ?', $subject->__toString());
    }

    public function testGetValuesWithOffset()
    {
        $subject = new SqlSrv\Clause\Offset(10, 25);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }

    public function testGetValuesWithoutOffset()
    {
        $subject = new SqlSrv\Clause\Offset(10);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(1, $subject->getValues());
    }
}
