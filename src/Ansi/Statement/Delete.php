<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Ansi\Statement;

use FaaPz\PDO\QueryBuilder\Ansi;
use PDO;

class Delete extends Ansi\AbstractStatement
{
    /** @var string|array<string, string>|null $table */
    protected $table = null;

    /**
     * @param PDO                               $dbh
     * @param string|array<string, string>|null $table
     */
    public function __construct(PDO $dbh, $table = null)
    {
        parent::__construct($dbh);

        if (!empty($table)) {
            $this->from($table);
        }
    }

    /**
     * @param string|array<string, string> $table
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
            trigger_error('No table set for delete statement', E_USER_ERROR);
        }

        if (is_array($this->table)) {
            $table = reset($this->table);
            $alias = key($this->table);
            if (is_string($alias)) {
                $table .= " AS {$alias}";
            }
        } else {
            $table = $this->table;
        }

        return " FROM {$table}";
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

        if (!empty($this->orderBy)) {
            $values = array_merge($values, $this->orderBy);
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'DELETE'
            . $this->renderFrom()
            . $this->renderJoin()
            . $this->renderWhere()
            . $this->renderOrderBy();
    }
}
