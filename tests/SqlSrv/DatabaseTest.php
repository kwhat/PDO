<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SqlSrv;

use FaaPz\PDO\QueryBuilder\SqlSrv\Database;
use FaaPz\PDO\QueryBuilder\SqlSrv\Statement\Exec;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /** @var Database $subject */
    private $subject;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getMockForAbstractClass(
            Database::class,
            [],
            '',
            false
        );
    }

    /**
     * @return void
     */
    public function testCall(): void
    {
        $this->assertInstanceOf(
            Exec::class,
            $this->subject->execute()
        );
    }
}
