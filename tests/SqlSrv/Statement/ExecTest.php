<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SqlSrv\Statement;

use FaaPz\PDO\QueryBuilder\SqlSrv\Clause\Method;
use FaaPz\PDO\QueryBuilder\SqlSrv\Statement\Exec;
use PDO;
use PHPUnit\Framework\TestCase;

class ExecTest extends TestCase
{
    /** @var Exec $subject */
    private Exec $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new Exec($this->createMock(PDO::class));
    }

    public function testToString()
    {
        $this->subject->method(new Method('MyFunc'));

        $this->assertStringStartsWith('EXEC', $this->subject->__toString());
    }

    public function testToStringWithoutMethod()
    {
        $this->expectError();
        $this->expectErrorMessageMatches('/^No method set for call statement/');

        $this->subject->__toString();
    }

    public function testGetValues()
    {
        $this->subject->method(new Method('MyFunc', 1, 2));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }
}
