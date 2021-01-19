<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\Test\SqlSrv\Statement;

use FaaPz\PDO\SqlSrv\Clause\Top;
use FaaPz\PDO\SqlSrv\Statement\Delete;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    /** @var PDO */
    private $pdo;

    /** @var Delete $subject */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')
            ->with($this->anything())
            ->willReturn($stmt);
        $stmt->method('rowCount')
            ->willReturn(1);

        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')
            ->with($this->anything())
            ->willReturn($stmt);

        $this->subject = new Delete($this->pdo);
    }

    public function testToStringWithLimit()
    {
        $this->subject
            ->from('test')
            ->limit(new Top(
                25
            ));

        $this->assertStringEndsWith('test TOP ?', $this->subject->__toString());
    }

    public function testGetValuesWithLimit()
    {
        $this->subject
            ->from('test')
            ->limit(new Top(
                25
            ));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(1, $this->subject->getValues());
    }
}
