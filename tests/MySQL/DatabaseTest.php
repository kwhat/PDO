<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL\Test;

use FaaPz\PDO\MySQL\Database;
use FaaPz\PDO\MySQL\Statement\Call;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DatabaseTest extends TestCase
{
    /** @var Database $subject */
    private $subject;

    public function setUp(): void
    {
        $ref = new ReflectionClass(Database::class);
        $this->subject = $ref->newInstanceWithoutConstructor();
    }

    public function testCall()
    {
        $this->assertInstanceOf(
            Call::class,
            $this->subject->call()
        );
    }
}
