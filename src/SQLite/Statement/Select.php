<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SQLite\Statement;

use FaaPz\PDO\QueryBuilder\QueryInterface;
use FaaPz\PDO\QueryBuilder\SQLite\AbstractStatement;
use FaaPz\PDO\QueryBuilder\SQLite\Database;
use FaaPz\PDO\QueryBuilder\SQLite\Clause\ConditionalInterface;

class Select extends AbstractStatement
{
    /** @var ?string|?array<string, string|self> $table */
    protected $table = null;

    /** @var bool $distinct */
    protected bool $distinct = false;

    /** @var array<int|string, string|self> $columns */
    protected $columns = [];

    /** @var array<int, self> $union */
    protected array $union = [];

    /** @var array<int, self> $unionAll */
    protected array $unionAll = [];

    /** @var array<int, string> $groupBy */
    protected array $groupBy = [];

    /** @var ?ConditionalInterface $having */
    protected ?ConditionalInterface $having = null;


    /**
     * @param Database $dbh
     * @param string[] $columns
     */
    public function __construct(Database $dbh, array $columns = ['*'])
    {
        parent::__construct($dbh);

        $this->columns($columns);
    }

    /**
     * @return $this
     */
    public function distinct(): self
    {
        $this->distinct = true;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderDistinct(): string
    {
        $sql = '';
        if ($this->distinct) {
            $sql = ' DISTINCT';
        }

        return $sql;
    }

    /**
     * @param array<int|string, string|self> $columns
     *
     * @return $this
     */
    public function columns(array $columns = ['*']): self
    {
        if (empty($columns)) {
            $this->columns = ['*'];
        } else {
            $this->columns = $columns;
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function renderColumns(): string
    {
        // FIXME Rewrite as a while loop, raise error if columns is empty.
        $columns = '';
        foreach ($this->columns as $key => $value) {
            if (!empty($columns)) {
                $columns .= ', ';
            }

            if ($value instanceof QueryInterface) {
                $column = "({$value})";
            } else {
                $column = $value;
            }

            if (is_string($key)) {
                $column .= " AS {$key}";
            }

            $columns .= $column;
        }

        return " {$columns}";
    }

    /**
     * @param string|array<string, string|self> $table
     *
     * @return $this
     */
    public function from($table): self
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderFrom(): string
    {
        if (empty($this->table)) {
            trigger_error('No table set for select statement', E_USER_ERROR);
        }

        if (is_array($this->table)) {
            $table = reset($this->table);
            if ($table instanceof QueryInterface) {
                $table = "({$table})";
            }

            $alias = key($this->table);
            if (is_string($alias)) {
                $table .= " AS {$alias}";
            }
        } else {
            $table = "{$this->table}";
        }

        return " FROM {$table}";
    }

    /**
     * @param self $query
     *
     * @return $this
     */
    public function union(self $query): self
    {
        $this->union[$this->getUnionCount()] = $query;

        return $this;
    }

    /**
     * @param self $query
     *
     * @return $this
     */
    public function unionAll(self $query): self
    {
        $this->unionAll[$this->getUnionCount()] = $query;

        return $this;
    }

    /**
     * @return int
     */
    protected function getUnionCount(): int
    {
        return count($this->union) + count($this->unionAll);
    }

    /**
     * @return string
     */
    protected function renderUnion(): string
    {
        $sql = '';
        for ($i = 0; $i < $this->getUnionCount(); $i++) {
            if (isset($this->union[$i])) {
                $union = "({$this->union[$i]})";
            } elseif (isset($this->unionAll[$i])) {
                $union = "ALL ({$this->unionAll[$i]})";
            } else {
                trigger_error('Union offset mismatch', E_USER_ERROR);
            }

            $sql .= " UNION {$union}";
        }

        return $sql;
    }


    /**
     * @param string ...$columns
     *
     * @return $this
     */
    public function groupBy(string ...$columns): self
    {
        $this->groupBy = array_merge($this->groupBy, $columns);

        return $this;
    }

    /**
     * @return string
     */
    protected function renderGroupBy(): string
    {
        $sql = '';
        if (!empty($this->groupBy)) {
            $sql = ' GROUP BY ' . implode(', ', $this->groupBy);
        }

        return $sql;
    }

    /**
     * @param ?ConditionalInterface $clause
     *
     * @return $this
     */
    public function having(?ConditionalInterface $clause): self
    {
        $this->having = $clause;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderHaving(): string
    {
        $sql = '';
        if ($this->having != null) {
            $sql = " HAVING {$this->having}";
        }

        return $sql;
    }

    /**
     * @return array<int, mixed>
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->join as $join) {
            $values = array_merge($values, $join->getValues());
        }

        if ($this->where != null) {
            $values = array_merge($values, $this->where->getValues());
        }

        if ($this->having != null) {
            $values = array_merge($values, $this->having->getValues());
        }

        if ($this->limit != null) {
            $values = array_merge($values, $this->limit->getValues());
        }

        for ($i = 0; $i < $this->getUnionCount(); $i++) {
            if (isset($this->union[$i])) {
                $values = array_merge($values, $this->union[$i]->getValues());
            } elseif (isset($this->unionAll[$i])) {
                $values = array_merge($values, $this->unionAll[$i]->getValues());
            } else {
                trigger_error('Union offset mismatch', E_USER_ERROR);
            }
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = 'SELECT'
            . $this->renderDistinct()
            . $this->renderColumns()
            . $this->renderFrom()
            . $this->renderJoin()
            . $this->renderWhere()
            . $this->renderGroupBy()
            . $this->renderHaving()
            . $this->renderOrderBy()
            . $this->renderLimit();

        if ($this->getUnionCount() > 0) {
            $sql = "({$sql})" . $this->renderUnion();
        }

        return $sql;
    }
}
