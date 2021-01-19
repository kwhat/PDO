<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv\Statement;

use FaaPz\PDO\QueryBuilder\Ansi;
use FaaPz\PDO\QueryBuilder\SqlSrv;

class Delete extends Ansi\Statement\Delete
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
     * @return string
     */
    public function __toString(): string
    {
        if (empty($this->table)) {
            trigger_error('No table is set for delete statement', E_USER_ERROR);
        }

        $sql = 'DELETE';
        if ($this->limit != null) {
            $sql .= " {$this->limit}";
        }

        if (is_array($this->table)) {
            reset($this->table);
            $alias = key($this->table);

            $table = $this->table[$alias];
            if (is_string($alias)) {
                $table .= " AS {$alias}";
            }
        } else {
            $table = "{$this->table}";
        }
        $sql .= " FROM {$table}";

        if (!empty($this->join)) {
            $sql .= ' ' . implode(' ', $this->join);
        }

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
