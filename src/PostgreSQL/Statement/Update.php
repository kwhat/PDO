<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\PostgreSQL\Statement;

use FaaPz\PDO\QueryBuilder\PostgreSQL\AbstractStatement;
use FaaPz\PDO\QueryBuilder\PostgreSQL\Database;
use FaaPz\PDO\QueryBuilder\QueryInterface;

class Update extends AbstractStatement
{
    /** @var string $table */
    protected $table;

    /** @var array<string, mixed> $pairs */
    protected $pairs;


    /**
     * @param Database             $dbh
     * @param array<string, mixed> $pairs
     */
    public function __construct(Database $dbh, array $pairs = [])
    {
        parent::__construct($dbh);

        $this->pairs = $pairs;
    }

    /**
     * @param string $table
     *
     * @return self
     */
    public function table(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderTable(): string
    {
        if (empty($this->table)) {
            trigger_error('No table set for update statement', E_USER_ERROR);
        }

        return " {$this->table}";
    }

    /**
     * @param array<string, mixed> $pairs
     *
     * @return $this
     */
    public function pairs(array $pairs): self
    {
        $this->pairs = array_merge($this->pairs, $pairs);

        return $this;
    }

    /**
     * @param string $column
     * @param mixed  $value
     *
     * @return $this
     */
    public function set(string $column, $value): self
    {
        $this->pairs[$column] = $value;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderSet(): string
    {
        if (empty($this->pairs)) {
            trigger_error('No column / value pairs set for update statement', E_USER_ERROR);
        }

        $sql = '';
        foreach ($this->pairs as $key => $value) {
            if (!empty($sql)) {
                $sql .= ', ';
            }

            if ($value instanceof QueryInterface) {
                $sql .= "{$key} = ({$value})";
            } else {
                $sql .= "{$key} = ?";
            }
        }

        return " SET {$sql}";
    }

    /**
     * @return array<int, mixed>
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->pairs as $value) {
            if ($value instanceof QueryInterface) {
                $values = array_merge($values, $value->getValues());
            } else {
                $values[] = $value;
            }
        }

        if ($this->where !== null) {
            $values = array_merge($values, $this->where->getValues());
        }

        if ($this->limit != null) {
            $values = array_merge($values, $this->limit->getValues());
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'UPDATE'
            . $this->renderTable()
            . $this->renderJoin()
            . $this->renderSet()
            . $this->renderWhere()
            . $this->renderOrderBy()
            . $this->renderLimit();
    }
}
