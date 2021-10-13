<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\AbstractStatement;
use FaaPz\PDO\QueryBuilder\QueryInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Database;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\Raw;

class Insert extends AbstractStatement
{
    /** @var ?string $table */
    protected ?string $table;

    /** @var array<string> $columns */
    protected array $columns = [];

    /** @var array<mixed> $values */
    protected array $values = [];

    /** @var bool $ignore */
    protected bool $ignore = false;

    /** @var array<string, mixed> $update */
    protected array $update = [];

    /**
     * @param Database             $dbh
     * @param array<string, mixed> $pairs
     */
    public function __construct(Database $dbh, array $pairs = [])
    {
        parent::__construct($dbh);

        $this->pairs($pairs);
    }

    /**
     * @return self
     */
    public function ignore(): self
    {
        $this->ignore = true;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderIgnore(): string
    {
        $sql = '';
        if ($this->ignore) {
            $sql = ' IGNORE';
        }

        return $sql;
    }


    /**
     * @param string $table
     *
     * @return self
     */
    public function into(?string $table): self
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderInto(): string
    {
        if (empty($this->table)) {
            trigger_error('No table set for insert statement', E_USER_ERROR);
        }

        return " INTO {$this->table}";
    }

    /**
     * @param array<string, mixed> $pairs
     *
     * @return $this
     */
    public function pairs(array $pairs): self
    {
        $this->columns(...array_keys($pairs));
        $this->values(...array_values($pairs));

        return $this;
    }

    /**
     * @param string ...$columns
     *
     * @return $this
     */
    public function columns(string ...$columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderColumns(): string
    {
        $sql = '';
        if (!empty($this->columns)) {
            $sql = ' (' . implode(', ', $this->columns) . ')';
        }

        return $sql;
    }

    /**
     * @param mixed ...$values
     *
     * @return $this
     */
    public function values(...$values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderValues(): string
    {
        $size = count($this->values);
        if ($size < 1) {
            trigger_error('No values set for insert statement', E_USER_ERROR);
        }

        if (count($this->columns) > 0 && count($this->columns) != count($this->values)) {
            trigger_error('No values set for insert statement', E_USER_ERROR);
        }

        if ($this->values[0] instanceof Select) {
            if (count($this->values) > 1) {
                trigger_error('Ignoring additional values after select for insert statement', E_USER_WARNING);
            }

            $placeholders = " {$this->values[0]}";
        } elseif (is_array($this->values[0])) {
            // FIXME this plug to use a loop instead of str_rep.
            $plug = substr(str_repeat('?, ', count($this->values[0])), 0, -2);
            $placeholders = " VALUES ({$plug})";

            for ($i = 1; $i < $size; $i++) {
                if (!is_array($this->values[$i])) {
                    trigger_error('Invalid nested value for insert statement', E_USER_ERROR);
                }

                if (count($this->values[0]) != count($this->values[$i])) {
                    trigger_error('Invalid nested value count for insert statement', E_USER_ERROR);
                }

                $plug = substr(str_repeat('?, ', count($this->values[$i])), 0, -2);
                $placeholders .= ", ({$plug})";
            }
        } else {
            if ($this->values[0] instanceof Raw) {
                $plug = "{$this->values[0]}";
            } elseif (is_scalar($this->values[0]) || $this->values[0] === null) {
                $plug = '?';
            } else {
                trigger_error('Invalid value for insert statement', E_USER_ERROR);
            }

            for ($i = 1; $i < $size; $i++) {
                if ($this->values[$i] instanceof Raw) {
                    $plug .= ", {$this->values[$i]}";
                } elseif (is_scalar($this->values[$i]) || $this->values[$i] === null) {
                    $plug .= ', ?';
                } else {
                    trigger_error('Invalid value for insert statement', E_USER_ERROR);
                }
            }

            $placeholders = " VALUES ({$plug})";
        }

        return $placeholders;
    }

    /**
     * @param array<string, mixed> $paris
     *
     * @return self
     */
    public function onDuplicateUpdate(array $paris = []): self
    {
        $this->update = $paris;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderOnDuplicateUpdate(): string
    {
        $sql = '';
        if (!empty($this->update)) {
            $sql = ' ON DUPLICATE KEY UPDATE';
            foreach ($this->update as $column => $value) {
                if (!$value instanceof QueryInterface) {
                    $value = '?';
                }

                $sql .= " {$column} = {$value}, ";
            }
            $sql = substr($sql, 0, -2);
        }

        return $sql;
    }

    /**
     * @return array<mixed>
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->values as $value) {
            if ($value instanceof QueryInterface) {
                $values = array_merge($values, $value->getValues());
            } else {
                $values[] = $value;
            }
        }

        foreach ($this->update as $value) {
            if ($value instanceof QueryInterface) {
                $values = array_merge($values, $value->getValues());
            } else {
                $values[] = $value;
            }
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'INSERT'
            . $this->renderIgnore()
            . $this->renderInto()
            . $this->renderColumns()
            . $this->renderValues()
            . $this->renderOnDuplicateUpdate();
    }
}
