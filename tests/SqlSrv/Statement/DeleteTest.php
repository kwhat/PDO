<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SqlSrv\Statement;

use FaaPz\PDO\QueryBuilder\SqlSrv\Database;
use FaaPz\PDO\QueryBuilder\SqlSrv\Clause\Top;
use FaaPz\PDO\QueryBuilder\SqlSrv\Statement\Delete;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    /** @var Database $database */
    private Database $database;

    /** @var Delete $subject */
    private Delete $subject;

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

    public function testToStringWithLimit()
    {
        $this->subject
            ->top(new Top(
                25
            ))
            ->from('test');

        $this->assertStringStartsWith('DELETE TOP ?', $this->subject->__toString());
    }

    public function testGetValuesWithLimit()
    {
        $this->subject
            ->top(new Top(
                25
            ))
            ->from('test');

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(1, $this->subject->getValues());
    }
}
