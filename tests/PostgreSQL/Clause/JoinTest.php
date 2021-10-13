<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\PostgreSQL\Clause;

use FaaPz\PDO\QueryBuilder\PostgreSQL\Database;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Conditional;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Grouping;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Join;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Statement\Select;
use PHPUnit\Framework\TestCase;

class JoinTest extends TestCase
{
    /**
     * @return void
     */
    public function testToString(): void
    {
        $subject = new Join(
            'table',
            new Conditional('column', '=', 'value')
        );

        $this->assertEquals('JOIN table ON column = ?', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithType(): void
    {
        $subject = new Join(
            'table',
            new Conditional('column', '=', 'value'),
            'INNER'
        );

        $this->assertEquals('INNER JOIN table ON column = ?', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithTableAlias(): void
    {
        $subject = new Join(
            ['alias' => 'table'],
            new Conditional('column1', '=', 'value1')
        );

        $this->assertEquals('JOIN table AS alias ON column1 = ?', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithTableAliasException(): void
    {
        $subject = new Join(
            false,
            new Conditional('column1', '=', 'value1')
        );

        $this->expectError();
        $this->expectErrorMessageMatches('/^Invalid subject value/');

        $subject->__toString();
    }

    /**
     * @return void
     */
    public function testToStringWithSelectTableAlias(): void
    {
        $subject = new Join(
            ['alias' => (new Select($this->createMock(Database::class)))->from('table')],
            new Conditional('column1', '=', 'value1')
        );

        $this->assertStringMatchesFormat('JOIN (SELECT * FROM table) AS alias ON column1 = ?', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithInvalidTableAlias(): void
    {
        $subject = new Join(
            ['alias', 'table'],
            new Conditional('column1', '=', 'value1')
        );

        $this->expectError();
        $this->expectErrorMessageMatches('/^Invalid subject array/');

        $subject->__toString();
    }

    /**
     * @return void
     */
    public function testToStringWithGrouping(): void
    {
        $subject = new Join(
            'table',
            new Grouping(
                'AND',
                new Conditional('column1', '=', 'value1'),
                new Conditional('column2', '=', 'value2')
            )
        );

        $this->assertEquals('JOIN table ON column1 = ? AND column2 = ?', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testGetValues(): void
    {
        $subject = new Join(
            'table',
            new Conditional('column', '=', 'value')
        );

        $this->assertIsArray($subject->getValues());
        $this->assertCount(1, $subject->getValues());
    }
}
