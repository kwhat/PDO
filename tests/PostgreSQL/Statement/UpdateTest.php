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
use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Raw;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Statement\Update;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    /** @var Update $subject */
    private Update $subject;

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

        $pdo = $this->createMock(Database::class);
        $pdo->method('prepare')
            ->with($this->anything())
            ->willReturn($stmt);

        $this->subject = new Update($pdo);
    }

    /**
     * @return void
     */
    public function testToString(): void
    {
        $this->subject
            ->table('test')
            ->set('col', 'value');

        $this->assertStringStartsWith('UPDATE test SET col = ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithPairs(): void
    {
        $this->subject
            ->table('test')
            ->pairs([
                'col1' => 'value1',
                'col2' => 'value2',
            ]);

        $this->assertStringStartsWith('UPDATE test SET col1 = ?, col2 = ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithRaw(): void
    {
        $this->subject
            ->table('test')
            ->set('col', new Raw('col + 1'));

        $this->assertStringStartsWith('UPDATE test SET col = (col + 1)', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithJoin(): void
    {
        $this->subject
            ->table('test1')
            ->set('col', 'value')
            ->join(new Join(
                'test2',
                new Conditional('test1.id', '=', 'test2.id')
            ));

        $this->assertStringStartsWith('UPDATE test1 JOIN test2 ON test1.id = ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithWhere(): void
    {
        $this->subject
            ->table('test')
            ->set('col', 'value')
            ->where(new Conditional('id', '=', 1));

        $this->assertStringEndsWith('? WHERE id = ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithOrderBy(): void
    {
        $this->subject
            ->table('test')
            ->set('col', 'value')
            ->orderBy('id', 'ASC')
            ->orderBy('name', 'DESC');

        $this->assertStringEndsWith(' ORDER BY id ASC, name DESC', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithoutTable(): void
    {
        $this->expectError();
        $this->expectErrorMessageMatches('/^No table set for update statement/');

        $this->subject->execute();
    }

    /**
     * @return void
     */
    public function testToStringWithoutPairs(): void
    {
        $this->expectError();
        $this->expectErrorMessageMatches('/^No column \/ value pairs set for update statement/');

        $this->subject
            ->table('test')
            ->execute();
    }

    /**
     * @return void
     */
    public function testGetValues(): void
    {
        $this->subject
            ->table('test')
            ->set('col', 'value');

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(1, $this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithPairs(): void
    {
        $this->subject
            ->table('test')
            ->pairs([
                'col1' => 'value1',
                'col2' => 'value2',
            ]);

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithWhere(): void
    {
        $this->subject
            ->table('test')
            ->set('col', 'value')
            ->where(new Conditional('col', '<>', 5));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }

    /**
     * @return void
     */
    public function testGetValuesWithOrderBy(): void
    {
        $this->subject
            ->table('test')
            ->set('col', 'value')
            ->orderBy('id', 'ASC')
            ->orderBy('name', 'DESC');

        // FIXME This seems broken...
        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(1, $this->subject->getValues());
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
    public function testToStringWithLimit(): void
    {
        $this->subject
            ->table('test')
            ->set('col', 'value')
            ->limit(new Limit(
                25,
                100
            ));

        $this->assertStringEndsWith(' LIMIT ? OFFSET ?', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testGetValuesWithLimit(): void
    {
        $this->subject
            ->table('test')
            ->set('col', 'value')
            ->limit(new Limit(
                25,
                100
            ));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(3, $this->subject->getValues());
    }
}
