<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Tests\SQLite\Clause;

use FaaPz\PDO\QueryBuilder\SQLite;
use PHPUnit\Framework\TestCase;

class RawTest extends TestCase
{
    /** @var SQLite\Clause\Raw $subject */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new SQLite\Clause\Raw('test');
    }

    public function testToString()
    {
        $this->assertEquals('test', $this->subject->__toString());
    }

    public function testGetValues()
    {
        $this->assertIsArray($this->subject->getValues());
        $this->assertEmpty($this->subject->getValues());
    }
}
