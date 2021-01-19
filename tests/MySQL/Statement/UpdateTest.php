<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL\Test\Statement;

use FaaPz\PDO\MySQL\Clause\Limit;
use FaaPz\PDO\MySQL\Statement\Update;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    /** @var Update $subject */
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

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')
            ->with($this->anything())
            ->willReturn($stmt);

        $this->subject = new Update($pdo);
    }

    public function testToStringWithLimit()
    {
        $this->subject
            ->table('test')
            ->set('col', 'value')
            ->limit(new Limit(
                25,
                100
            ));

        $this->assertStringEndsWith(' LIMIT ?, ?', $this->subject->__toString());
    }

    public function testGetValuesWithLimit()
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
