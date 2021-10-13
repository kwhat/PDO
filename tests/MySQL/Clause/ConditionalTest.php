<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\MySQL\Clause;

use FaaPz\PDO\QueryBuilder\MySQL\Clause\Conditional;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\Method;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\Raw;
use PHPUnit\Framework\TestCase;

class ConditionalTest extends TestCase
{
    /**
     * @return void
     */
    public function testToString(): void
    {
        $subject = new Conditional('col', '=', 'val');

        $this->assertEquals('col = ?', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithIn(): void
    {
        $subject = new Conditional('col', 'IN', [1, 2, 3]);

        $this->assertEquals('col IN (?, ?, ?)', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithInException(): void
    {
        $subject = new Conditional('col', 'IN', []);

        $this->expectError();
        $this->expectErrorMessageMatches("/Conditional operator 'IN' requires at least one argument/");

        $subject->__toString();
    }

    /**
     * @return void
     */
    public function testToStringWithBetween(): void
    {
        $subject = new Conditional('col', 'BETWEEN', [1, 2]);

        $this->assertEquals('col BETWEEN (? AND ?)', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithBetweenException(): void
    {
        $subject = new Conditional('col', 'BETWEEN', [1, 2, 3]);

        $this->expectError();
        $this->expectErrorMessage("Conditional operator 'BETWEEN' requires two arguments");

        $subject->__toString();
    }

    /**
     * @return void
     */
    public function testToStringWithQuery(): void
    {
        $subject = new Conditional('col', '=', new Raw(1));

        $this->assertEquals('col = 1', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithQueryAndArgs(): void
    {
        $subject = new Conditional('col', '=', new Method('test', 1, 2));

        $this->assertEquals('col = test(?, ?)', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testGetValues(): void
    {
        $subject = new Conditional('col', '=', 'val');

        $this->assertIsArray($subject->getValues());
        $this->assertCount(1, $subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithQuery(): void
    {
        $subject = new Conditional('col', '=', new Raw(1));

        $this->assertIsArray($subject->getValues());
        $this->assertCount(0, $subject->getValues());
        $this->assertEquals('col = 1', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testGetValuesWithQueryAndArgs(): void
    {
        $subject = new Conditional('col', '=', new Method('test', 1, 2));

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }
}
