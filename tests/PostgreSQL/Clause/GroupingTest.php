<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\PostgreSQL\Clause;

use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Conditional;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Grouping;
use PHPUnit\Framework\TestCase;

class GroupingTest extends TestCase
{
    /**
     * @return void
     */
    public function testToString(): void
    {
        $subject = new Grouping(
            'AND',
            new Conditional('column1', '=', 'value1'),
            new Conditional('column2', '=', 'value2')
        );

        $this->assertEquals('column1 = ? AND column2 = ?', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringNestedSelf(): void
    {
        $subject = new Grouping(
            'AND',
            new Conditional('column1', '=', 'value1'),
            new Grouping(
                'OR',
                new Conditional('column2', '=', 'value2'),
                new Conditional('column3', '=', 'value3')
            )
        );

        $this->assertEquals('column1 = ? AND (column2 = ? OR column3 = ?)', $subject->__toString());
    }

    /**
     * @return void
     */
    public function testGetValues(): void
    {
        $subject = new Grouping(
            'AND',
            new Conditional('column1', '=', 'value1'),
            new Conditional('column2', '=', 'value2')
        );

        $this->assertIsArray($subject->getValues());
        $this->assertCount(2, $subject->getValues());
    }
}
