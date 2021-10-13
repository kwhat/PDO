<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv\Clause;

class Top implements TopInterface
{
    /** @var int $size */
    protected int $size;

    /** @var bool $percent */
    protected bool $percent;

    /**
     * @param int  $size
     * @param bool $percent
     */
    public function __construct(int $size, bool $percent = false)
    {
        $this->size = $size;
        $this->percent = $percent;
    }

    /**
     * @return int[]
     */
    public function getValues(): array
    {
        return [$this->size];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = 'TOP ?';
        if ($this->percent) {
            $sql .= ' PERCENT';
        }

        return $sql;
    }
}
