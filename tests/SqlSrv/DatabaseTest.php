<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\Test\SqlSrv;

use FaaPz\PDO\SqlSrv\Database;
use FaaPz\PDO\SqlSrv\Statement\Exec;
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
            Exec::class,
            $this->subject->execute()
        );
    }
}
