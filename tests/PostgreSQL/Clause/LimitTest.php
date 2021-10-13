<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\PostgreSQL\Clause;

use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Limit;
use PHPUnit\Framework\TestCase;

class LimitTest extends TestCase
{
    /**
     * @return void
     */
    public function testToStringWithOffset(): void
    {
        $subject = new Limit(10, 25);

        $this->assertEquals('LIMIT ? OFFSET ?', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithoutOffset(): void
    {
        $subject = new Limit(10);

        $this->assertEquals('LIMIT ?', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testGetValuesWithOffset(): void
    {
        $subject = new Limit(10, 25);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithoutOffset(): void
    {
        $subject = new Limit(10);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(1, $subject->getValues());
    }
}
