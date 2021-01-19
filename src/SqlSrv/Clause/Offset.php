<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv\Clause;

use FaaPz\PDO\QueryBuilder\QueryInterface;

class Offset implements QueryInterface
{
    /** @var int $offset */
    protected $offset;

    /** @var int|null $size */
    protected $size;

    /**
     * @param int      $offset
     * @param int|null $size
     */
    public function __construct(int $offset, ?int $size = null)
    {
        $this->offset = $offset;
        $this->size = $size;
    }

    /**
     * @return int[]
     */
    public function getValues(): array
    {
        $values = [$this->offset];
        if (isset($this->size)) {
            $values[] = $this->size;
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = 'OFFSET ?';
        if ($this->size !== null) {
            $sql .= ' ROWS FETCH NEXT ? ROWS ONLY';
        }

        return $sql;
    }
}
