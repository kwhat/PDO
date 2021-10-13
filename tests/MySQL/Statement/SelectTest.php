<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\MySQL\Database;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\Conditional;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\Join;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\Limit;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\Select;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    /** @var Select $subject */
    protected Select $subject;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new Select($this->createMock(Database::class));
    }

    /**
     * @return void
     */
    public function testToString(): void
    {
        $this->subject
            ->columns(['id', 'name'])
            ->from('test');

        $this->assertEquals('SELECT id, name FROM test', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithColumnAlias(): void
    {
        $this->subject
            ->columns(['id' => 'pk'])
            ->from('test');

        $this->assertStringEndsWith('pk AS id FROM test', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithColumnSubQuery(): void
    {
        $this->subject
            ->columns(['sub' => (new Select($this->createMock(Database::class)))->from('test2')])
            ->from('test1');

        $this->assertStringEndsWith('(SELECT * FROM test2) AS sub FROM test1', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithTableAlias(): void
    {
        $this->subject
            ->from(['alias' => 'test']);

        $this->assertStringEndsWith('FROM test AS alias', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithTableSubQuery(): void
    {
        $this->subject
            ->from(['sub' => (new Select($this->createMock(Database::class)))->from('test')]);

        $this->assertEquals('SELECT * FROM (SELECT * FROM test) AS sub', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithDistinct(): void
    {
        $this->subject
            ->distinct()
            ->from('test');

        $this->assertStringStartsWith('SELECT DISTINCT * FROM test', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithColumns(): void
    {
        $this->subject
            ->from('test')
            ->columns(['col1', 'col2']);

        $this->assertStringStartsWith('SELECT col1, col2 FROM test', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithoutColumns(): void
    {
        $this->subject
            ->from('test');

        $this->assertStringStartsWith('SELECT * FROM test', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringEmptyColumns(): void
    {
        $this->subject
            ->from('test')
            ->columns([])
            ->columns();

        $this->assertStringStartsWith('SELECT * FROM test', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithJoin(): void
    {
        $this->subject
            ->from('test1')
            ->join(new Join(
                'test2',
                new Conditional('test1.id', '=', 'test2.id')
            ));

        $this->assertStringEndsWith('FROM test1 JOIN test2 ON test1.id = ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithWhere(): void
    {
        $this->subject
            ->from('test')
            ->where(new Conditional('id', '=', 1));

        $this->assertStringEndsWith('test WHERE id = ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithGroupBy(): void
    {
        $this->subject
            ->from('test')
            ->groupBy('id', 'name');

        $this->assertStringEndsWith('test GROUP BY id, name', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithHaving(): void
    {
        $this->subject
            ->from('test')
            ->having(new Conditional('id', '=', 1));

        $this->assertStringEndsWith('test HAVING id = ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithOrderBy(): void
    {
        $this->subject
            ->from('test')
            ->orderBy('id', 'ASC')
            ->orderBy('name', 'DESC');

        $this->assertStringEndsWith('test ORDER BY id ASC, name DESC', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithLimit(): void
    {
        $this->subject
            ->from('test')
            ->orderBy('id', 'ASC')
            ->limit(new Limit(5));

        $this->assertStringEndsWith('test ORDER BY id ASC LIMIT ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithoutOrderByWithLimitAndOffset(): void
    {
        $this->subject
            ->from('test')
            ->limit(new Limit(5, 25));

        $this->assertStringEndsWith('test LIMIT ? OFFSET ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithLimitAndOffset(): void
    {
        $this->subject
            ->from('test')
            ->orderBy('id', 'ASC')
            ->limit(new Limit(5, 25));

        $this->assertStringEndsWith(
            'test ORDER BY id ASC LIMIT ? OFFSET ?',
            $this->subject->__toString()
        );
    }

    /**
     * @return void
     */
    public function testToStringWithoutTable(): void
    {
        $this->expectError();
        $this->expectErrorMessageMatches('/^No table set for select statement/');

        $this->subject->execute();
    }

    /**
     * @return void
     */
    public function testGetValuesEmpty(): void
    {
        $this->assertIsArray($this->subject->getValues());
        $this->assertEmpty($this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithJoin(): void
    {
        $this->subject
            ->from('test1')
            ->join(new Join(
                'test2',
                new Conditional('test1.id', '=', 'test2.id')
            ));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(1, $this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithWhere(): void
    {
        $this->subject
            ->from('test')
            ->where(new Conditional('col', '<>', 5));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(1, $this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithUnion(): void
    {
        $this->subject
            ->columns(['id', 'name'])
            ->from('test1')
            ->union(
                (new Select($this->createMock(Database::class)))
                    ->columns(['id', 'name'])
                    ->from('test2')
            );

        $this->assertStringMatchesFormat(
            '(SELECT id, name FROM test1) UNION (SELECT id, name FROM test2)',
            $this->subject->__toString()
        );
    }

    /**
     * @return void
     */
    public function testGetValuesWithUnionAll(): void
    {
        $this->subject
            ->columns(['id', 'name'])
            ->from('test1')
            ->unionAll(
                (new Select($this->createMock(Database::class)))
                    ->columns(['id', 'name'])
                    ->from('test2')
            );

        $this->assertStringMatchesFormat(
            '(SELECT id, name FROM test1) UNION ALL (SELECT id, name FROM test2)',
            $this->subject->__toString()
        );
    }

    /**
     * @return void
     */
    public function testGetValuesWithUnionAndUnionAll(): void
    {
        $this->subject
            ->columns(['id', 'name'])
            ->from('test1')
            ->union(
                (new Select($this->createMock(Database::class)))
                    ->columns(['id', 'name'])
                    ->from('test2')
            )
            ->unionAll(
                (new Select($this->createMock(Database::class)))
                    ->columns(['id', 'name'])
                    ->from('test3')
            );

        $this->assertStringMatchesFormat(
            '(SELECT id, name FROM test1) UNION (SELECT id, name FROM test2) UNION ALL (SELECT id, name FROM test3)',
            $this->subject->__toString()
        );
    }

    /**
     * @return void
     */
    public function testGetValuesWithHaving(): void
    {
        $this->subject
            ->from('test')
            ->having(new Conditional('id', '=', 1));

        $this->assertCount(1, $this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithGroupBy(): void
    {
        $this->subject
            ->from('test')
            ->groupBy('id', 'name');

        $this->assertEmpty($this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithLimit(): void
    {
        $this->subject
            ->from('test')
            ->limit(new Limit(25, 100));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }
}
