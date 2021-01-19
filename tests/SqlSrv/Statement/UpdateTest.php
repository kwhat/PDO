<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\Test\SqlSrv\Statement;

use FaaPz\PDO\SqlSrv\Clause\Top;
use FaaPz\PDO\SqlSrv\Statement\Update;
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
            ->limit(new Top(
                25
            ));

        $this->assertEquals('UPDATE TOP ? test SET col = ?', $this->subject->__toString());
    }

    public function testGetValuesWithLimit()
    {
        $this->subject
            ->table('test')
            ->set('col', 'value')
            ->limit(new Top(
                25
            ));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }
}
