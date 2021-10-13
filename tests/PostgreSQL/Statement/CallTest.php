<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\PostgreSQL\Statement;

use FaaPz\PDO\QueryBuilder\PostgreSQL\Database;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Clause\Method;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Statement\Call;
use PHPUnit\Framework\TestCase;

class CallTest extends TestCase
{
    /** @var Call $subject */
    private Call $subject;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new Call($this->createMock(Database::class));
    }

    /**
     * @return void
     */
    public function testToString(): void
    {
        $this->subject->method(new Method('MyFunc'));

        $this->assertStringStartsWith('CALL', $this->subject->__toString());
    }

    /**
     * @return void
     */
    public function testToStringWithoutMethod(): void
    {
        $this->expectError();
        $this->expectErrorMessageMatches('/^No method set for call statement/');

        $this->subject->__toString();
    }

    /**
     * @return void
     */
    public function testGetValues(): void
    {
        $this->subject->method(new Method('MyFunc', 1, 2));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }
}
