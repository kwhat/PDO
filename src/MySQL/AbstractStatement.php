<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\ConditionalInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\JoinInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\LimitInterface;

abstract class AbstractStatement extends QueryBuilder\AbstractStatement implements StatementInterface
{
    /** @var array<JoinInterface> $join */
    protected array $join = [];

    /** @var ?ConditionalInterface $where */
    protected ?ConditionalInterface $where = null;

    /** @var array<string, string> $orderBy */
    protected array $orderBy = [];

    /** @var ?LimitInterface $limit */
    protected ?LimitInterface $limit = null;


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
     * @param LimitInterface $limit
     *
     * @return self
     */
    public function limit(LimitInterface $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderLimit(): string
    {
        $sql = '';
        if ($this->limit != null) {
            $sql = " {$this->limit}";
        }

        return $sql;
    }
}
