<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\SqlSrv\Clause\ConditionalInterface;
use FaaPz\PDO\QueryBuilder\SqlSrv\Clause\JoinInterface;
use FaaPz\PDO\QueryBuilder\SqlSrv\Clause\TopInterface;

abstract class AbstractStatement extends QueryBuilder\AbstractStatement
{
    /** @var array<JoinInterface> $join */
    protected array $join = [];

    /** @var ?ConditionalInterface $where */
    protected ?ConditionalInterface $where = null;

    /** @var array<string, string> $orderBy */
    protected array $orderBy = [];

    /** @var ?TopInterface $top */
    protected ?TopInterface $top = null;


    /**
     * @param JoinInterface $clause
     *
     * @return self
     */
    public function join(JoinInterface $clause): self
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
     * @param ConditionalInterface $clause
     *
     * @return self
     */
    public function where(ConditionalInterface $clause): self
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
     * @return self
     */
    public function orderBy(string $column, string $direction = ''): self
    {
        $this->orderBy[$column] = strtoupper(trim($direction));

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

    /**
     * @param ?TopInterface $limit
     *
     * @return self
     */
    public function top(?TopInterface $limit): self
    {
        $this->top = $limit;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderTop(): string
    {
        $sql = '';
        if ($this->top != null) {
            $sql = " {$this->top}";
        }

        return $sql;
    }
}
