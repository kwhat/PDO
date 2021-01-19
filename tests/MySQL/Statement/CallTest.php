<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL\Test\Statement;

use FaaPz\PDO\Clause\Method;
use FaaPz\PDO\MySQL\Statement\Call;
use PDO;
use PHPUnit\Framework\TestCase;

class CallTest extends TestCase
{
    /** @var Call $subject */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new Call($this->createMock(PDO::class));
    }

    public function testToString()
    {
        $this->subject->method(new Method('MyFunc'));

        $this->assertStringStartsWith('CALL', $this->subject->__toString());
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
