<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\PostgreSQL;

use FaaPz\PDO\QueryBuilder\PostgreSQL\Database;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Statement\Call;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Statement\Select;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Statement\Insert;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Statement\Update;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Statement\Delete;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class DatabaseTest extends TestCase
{
    /** @var Database $subject */
    private Database $subject;

    /**
     * @return void
     * @throws ReflectionException
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
            Call::class,
            $this->subject->call()
        );
    }

    /**
     * @return void
     */
    public function testSelect(): void
    {
        $this->assertInstanceOf(
            Select::class,
            $this->subject->select()
        );
    }

    /**
     * @return void
     */
    public function testInsert(): void
    {
        $this->assertInstanceOf(
            Insert::class,
            $this->subject->insert()
        );
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $this->assertInstanceOf(
            Update::class,
            $this->subject->update()
        );
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        $this->assertInstanceOf(
            Delete::class,
            $this->subject->delete()
        );
    }
}
