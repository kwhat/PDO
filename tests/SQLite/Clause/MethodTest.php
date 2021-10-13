<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SQLite\Clause;

use FaaPz\PDO\QueryBuilder\SQLite;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    public function testToString()
    {
        $subject = new SQLite\Clause\Method('test');

        $this->assertEquals('test()', $subject->__toString());
    }

    public function testToStringWithArgs()
    {
        $subject = new SQLite\Clause\Method('test', 1, 2);

        $this->assertEquals('test(?, ?)', $subject->__toString());
    }

    public function testToStringWithQuery()
    {
        $subject = new SQLite\Clause\Method('test', new SQLite\Clause\Raw(1));

        $this->assertEquals('test(1)', $subject->__toString());
    }

    public function testToStringWithQueryAndArgs()
    {
        $subject = new SQLite\Clause\Method('test', new SQLite\Clause\Method('next', 1, 2));

        $this->assertEquals('test(next(?, ?))', $subject->__toString());
    }

    public function testGetValues()
    {
        $subject = new SQLite\Clause\Method('test');

        $this->assertIsArray($subject->getValues());
        $this->assertEmpty($subject->getValues());
    }

    public function testGetValuesWithArgs()
    {
        $subject = new SQLite\Clause\Method('test', 1, 2);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }

    public function testGetValuesWithoutArgs()
    {
        $subject = new SQLite\Clause\Method('test');

        $this->assertIsArray($subject->getValues());
        $this->assertEmpty($subject->getValues());
    }

    public function testGetValuesWithQueryAndArgs()
    {
        $subject = new SQLite\Clause\Method('test', new SQLite\Clause\Method('next', 1, 2));

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }
}
