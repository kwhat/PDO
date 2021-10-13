<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\PostgreSQL\Statement;

use FaaPz\PDO\QueryBuilder\PostgreSQL\Database;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Conditional;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Join;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Limit;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Statement\Delete;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    /** @var Database */
    private Database $database;

    /** @var Delete $subject */
    private Delete $subject;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')
            ->with($this->anything())
            ->willReturn($stmt);
        $stmt->method('rowCount')
            ->willReturn(1);

        $this->database = $this->createMock(Database::class);
        $this->database->method('prepare')
            ->with($this->anything())
            ->willReturn($stmt);

        $this->subject = new Delete($this->database);
    }

    /**
     * @return void
     */
    public function testToString(): void
    {
        $this->subject->from('test');

        $this->assertStringStartsWith('DELETE FROM test', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithAlias(): void
    {
        $this->subject
            ->from(['alias' => 'test']);

        $this->assertStringEndsWith('test AS alias', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithConstructorTable(): void
    {
        $this->subject = new Delete($this->database, ['alias' => 'test']);

        $this->assertStringEndsWith('test AS alias', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithoutTable(): void
    {
        $this->expectError();
        $this->expectErrorMessageMatches('/^No table set for delete statement/');

        $this->subject->__toString();
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

        $this->assertStringEndsWith('test1 JOIN test2 ON test1.id = ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithWhere(): void
    {
        $this->subject
            ->from('test')
            ->where(new Conditional('id', '=', 1));

        $this->assertStringEndsWith('WHERE id = ?', $this->subject->__toString());
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

        // FIXME This seems broken...
        $this->assertStringEndsWith('test ORDER BY id ASC, name DESC', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testGetValues(): void
    {
        $this->assertIsArray($this->subject->getValues());
        $this->assertEmpty($this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithWhere(): void
    {
        $this->subject
            ->from('test')
            ->where(new Conditional('id', '=', 1));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(1, $this->subject->getValues());
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
    public function testGetValuesWithOrderBy(): void
    {
        $this->subject
            ->from('test1')
            ->orderBy('id', 'ASC')
            ->orderBy('name', 'DESC');

        // FIXME This seems broken...
        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testToStringWithLimit(): void
    {
        $this->subject
            ->from('test')
            ->limit(new Limit(
                25,
                100
            ));

        $this->assertStringEndsWith('test LIMIT ? OFFSET ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testGetValuesWithLimit(): void
    {
        $this->subject
            ->from('test')
            ->limit(new Limit(
                25,
                100
            ));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }
}
