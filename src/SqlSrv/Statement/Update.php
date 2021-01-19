<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv\Statement;

use FaaPz\PDO\QueryBuilder\Ansi;
use FaaPz\PDO\QueryBuilder\SqlSrv;

/**
 * @property array<int, Exec|Select> $union
 * @property array<int, Exec|Select> $unionAll
 */
class Update extends Ansi\Statement\Update
{
    /** @var SqlSrv\Clause\Top|null $limit */
    protected $limit = null;

    /**
     * @param SqlSrv\Clause\Top|null $limit
     *
     * @return Update
     */
    public function limit(?SqlSrv\Clause\Top $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function getValues(): array
    {
        $values = parent::getValues();
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
        if (!isset($this->table)) {
            trigger_error('No table is set for update statement', E_USER_ERROR);
        }

        if (empty($this->pairs)) {
            trigger_error('Missing columns and values for update statement', E_USER_ERROR);
        }

        $sql = 'UPDATE';
        if ($this->limit instanceof SqlSrv\Clause\Top) {
            $sql .= " {$this->limit}";
        }
        $sql .= " {$this->table}";

        if (!empty($this->join)) {
            $sql .= ' ' . implode(' ', $this->join);
        }

        $sql .= $this->renderSet();
        if ($this->where != null) {
            $sql .= " WHERE {$this->where}";
        }

        if ($direction = reset($this->orderBy)) {
            $column = key($this->orderBy);
            $sql .= " ORDER BY {$column} {$direction}";

            while ($direction = next($this->orderBy)) {
                $column = key($this->orderBy);
                $sql .= ", {$column} {$direction}";
            }
        }

        return $sql;
    }
}
