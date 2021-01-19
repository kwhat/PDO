<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Ansi\Clause;

use FaaPz\PDO\QueryBuilder;

class Raw implements QueryBuilder\QueryInterface
{
    /** @var string $sql */
    protected $sql;

    /**
     * @param string $sql
     */
    public function __construct(string $sql)
    {
        $this->sql = $sql;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->sql;
    }

    /**
     * @return mixed[]
     */
    public function getValues(): array
    {
        return [];
    }
}
