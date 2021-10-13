<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SqlSrv\Statement;

use FaaPz\PDO\QueryBuilder\SqlSrv\Database;
use FaaPz\PDO\QueryBuilder\SqlSrv\Clause\Top;
use FaaPz\PDO\QueryBuilder\SqlSrv\Statement\Update;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    /** @var Update $subject */
    private Update $subject;

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

    public function testToStringWithLimit()
    {
        $this->subject
            ->top(new Top(
                25
            ))
            ->table('test')
            ->set('col', 'value');

        $this->assertEquals('UPDATE TOP ? test SET col = ?', $this->subject->__toString());
    }

    public function testGetValuesWithLimit()
    {
        $this->subject
            ->top(new Top(
                25
            ))
            ->table('test')
            ->set('col', 'value');

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }
}
