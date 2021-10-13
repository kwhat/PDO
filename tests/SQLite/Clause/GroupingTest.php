<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SQLite\Clause;

use FaaPz\PDO\QueryBuilder\SQLite;
use PHPUnit\Framework\TestCase;

class GroupingTest extends TestCase
{
    public function testToString()
    {
        $subject = new SQLite\Clause\Grouping(
            'AND',
            new SQLite\Clause\Conditional('column1', '=', 'value1'),
            new SQLite\Clause\Conditional('column2', '=', 'value2')
        );

        $this->assertEquals('column1 = ? AND column2 = ?', $subject->__toString());
    }

    public function testToStringNestedSelf()
    {
        $subject = new SQLite\Clause\Grouping(
            'AND',
            new SQLite\Clause\Conditional('column1', '=', 'value1'),
            new SQLite\Clause\Grouping(
                'OR',
                new SQLite\Clause\Conditional('column2', '=', 'value2'),
                new SQLite\Clause\Conditional('column3', '=', 'value3')
            )
        );

        $this->assertEquals('column1 = ? AND (column2 = ? OR column3 = ?)', $subject->__toString());
    }

    public function testGetValues()
    {
        $subject = new SQLite\Clause\Grouping(
            'AND',
            new SQLite\Clause\Conditional('column1', '=', 'value1'),
            new SQLite\Clause\Conditional('column2', '=', 'value2')
        );

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }
}
