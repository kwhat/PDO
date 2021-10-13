<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SQLite;

use FaaPz\PDO\QueryBuilder\SQLite\Database;
use FaaPz\PDO\QueryBuilder\SQLite\Statement\Delete;
use FaaPz\PDO\QueryBuilder\SQLite\Statement\Insert;
use FaaPz\PDO\QueryBuilder\SQLite\Statement\Select;
use FaaPz\PDO\QueryBuilder\SQLite\Statement\Update;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /** @var Database $subject */
    private $subject;

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

    public function testSelect()
    {
        $this->assertInstanceOf(
            Select::class,
            $this->subject->select()
        );
    }

    public function testInsert()
    {
        $this->assertInstanceOf(
            Insert::class,
            $this->subject->insert()
        );
    }

    public function testUpdate()
    {
        $this->assertInstanceOf(
            Update::class,
            $this->subject->update()
        );
    }

    public function testDelete()
    {
        $this->assertInstanceOf(
            Delete::class,
            $this->subject->delete()
        );
    }
}
