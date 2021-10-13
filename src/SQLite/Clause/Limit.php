<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SQLite\Clause;

class Limit implements LimitInterface
{
    /** @var int $size */
    protected int $size;

    /** @var ?int $offset */
    protected ?int $offset;

    /**
     * @param int  $size
     * @param ?int $offset
     */
    public function __construct(int $size, ?int $offset = null)
    {
        $this->size = $size;
        $this->offset = $offset;
    }

    /**
     * @return array<int>
     */
    public function getValues(): array
    {
        $values = [$this->size];
        if ($this->offset !== null) {
            $values[] = $this->offset;
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = 'LIMIT ?';
        if ($this->offset !== null) {
            $sql .= ' OFFSET ?';
        }

        return $sql;
    }
}
