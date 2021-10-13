<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SqlSrv\Clause;

use FaaPz\PDO\QueryBuilder\SqlSrv;
use PHPUnit\Framework\TestCase;

class TopTest extends TestCase
{
    public function testToString()
    {
        $subject = new SqlSrv\Clause\Top(10);

        $this->assertEquals('TOP ?', $subject->__toString());
    }

    public function testToStringWithPrecent()
    {
        $subject = new SqlSrv\Clause\Top(10, true);

        $this->assertEquals('TOP ? PERCENT', $subject->__toString());
    }

    public function testGetValuesWithOffset()
    {
        $subject = new SqlSrv\Clause\Top(10);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(1, $subject->getValues());
    }
}
