<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Ansi;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\Ansi;

abstract class AbstractStatement extends QueryBuilder\AbstractStatement
{
    /** @var Ansi\Clause\Join[] $join */
    protected $join = [];

    /** @var Ansi\Clause\Conditional|null $where */
    protected $where = null;

    /** @var array<string, string> $orderBy */
    protected $orderBy = [];

    /**
     * @param Ansi\Clause\Join $clause
     *
     * @return $this
     */
    public function join(Ansi\Clause\Join $clause)
    {
        $this->join[] = $clause;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderJoin(): string
    {
        $sql = '';
        if (!empty($this->join)) {
            $sql = ' ' . implode(' ', $this->join);
        }

        return $sql;
    }

    /**
     * @param Ansi\Clause\Conditional $clause
     *
     * @return $this
     */
    public function where(Ansi\Clause\Conditional $clause)
    {
        $this->where = $clause;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderWhere(): string
    {
        $sql = '';
        if ($this->where !== null) {
            $sql = " WHERE {$this->where}";
        }

        return $sql;
    }

    /**
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy(string $column, string $direction = '')
    {
        $this->orderBy[$column] = $direction;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderOrderBy(): string
    {
        $sql = '';
        if ($direction = reset($this->orderBy)) {
            $column = key($this->orderBy);
            $sql = " ORDER BY {$column} {$direction}";

            while ($direction = next($this->orderBy)) {
                $column = key($this->orderBy);
                $sql .= ", {$column} {$direction}";
            }
        }

        return $sql;
    }
}
