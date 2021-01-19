<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL\Test\Statement;

use FaaPz\PDO\MySQL\Clause\Limit;
use FaaPz\PDO\MySQL\Statement\Delete;
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
            ->limit(new Limit(
                25,
                100
            ));

        $this->assertStringEndsWith('test LIMIT ?, ?', $this->subject->__toString());
    }

    public function testGetValuesWithLimit()
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
