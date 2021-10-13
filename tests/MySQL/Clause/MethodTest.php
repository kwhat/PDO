<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\MySQL\Clause;

use FaaPz\PDO\QueryBuilder\MySQL\Clause\Method;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\Raw;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /**
     * @return void
     */
    public function testToString()
    {
        $subject = new Method('test');

        $this->assertEquals('test()', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithArgs()
    {
        $subject = new Method('test', 1, 2);

        $this->assertEquals('test(?, ?)', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithQuery()
    {
        $subject = new Method('test', new Raw(1));

        $this->assertEquals('test(1)', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithQueryAndArgs()
    {
        $subject = new Method('test', new Method('next', 1, 2));

        $this->assertEquals('test(next(?, ?))', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testGetValues()
    {
        $subject = new Method('test');

        $this->assertIsArray($subject->getValues());
        $this->assertEmpty($subject->getValues());
    }

    public function testGetValuesWithArgs()
    {
        $subject = new Method('test', 1, 2);

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }

    public function testGetValuesWithoutArgs()
    {
        $subject = new Method('test');

        $this->assertIsArray($subject->getValues());
        $this->assertEmpty($subject->getValues());
    }

    public function testGetValuesWithQueryAndArgs()
    {
        $subject = new Method('test', new Method('next', 1, 2));

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }
}
