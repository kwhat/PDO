<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL\Test\Statement;

use FaapZ\PDO\MySQL;
use PDO;

/**
 * @property MySQL\Statement\Select $subject
 */
class SelectTest extends \FaaPz\PDO\Test\Statement\SelectTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new MySQL\Statement\Select($this->createMock(PDO::class));
    }

    public function testToStringWithLimit()
    {
        $this->subject
            ->from('test')
            ->limit(new MySQL\Clause\Limit(5, 25));

        $this->assertStringEndsWith('test LIMIT ?, ?', $this->subject->__toString());
    }

    public function testGetValuesWithLimit()
    {
        $this->subject
            ->from('test')
            ->limit(new MySQL\Clause\Limit(25, 100));

        $this->assertIsArray($this->subject->getValues());
        $this->assertCount(2, $this->subject->getValues());
    }
}
