<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\Statement;

use FaaPz\PDO\AbstractStatement;
use FaaPz\PDO\Clause\Raw;
use FaaPz\PDO\DatabaseException;
use FaaPz\PDO\QueryInterface;
use PDO;

class Insert extends AbstractStatement
{
    /** @var string $table */
    protected $table;

    /** @var string[] $columns */
    protected $columns = [];

    /** @var mixed[] $values */
    protected $values = [];

    /** @var bool $ignore */
    protected $ignore = false;

    /**
     * @param PDO                  $dbh
     * @param array<string, mixed> $pairs
     */
    public function __construct(PDO $dbh, array $pairs = [])
    {
        parent::__construct($dbh);

        $this->pairs($pairs);
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function into(string $table): self
    {
        $this->table = $table;

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
     * @return $this
     */
    public function ignore(): self
    {
        $this->ignore = true;

        return $this;
    }

    /**
     * @return string
     * @throws DatabaseException
     */
    public function __toString(): string
    {
        if (empty($this->table)) {
            throw new DatabaseException('No table is set for insertion');
        }

        $size = count($this->values);
        if ($size < 1) {
            throw new DatabaseException('Missing columns for insertion');
        }

        if (count($this->columns) > 0 && count($this->columns) != count($this->values)) {
            throw new DatabaseException('Missing values for insertion');
        }

        if ($this->values[0] instanceof Select) {
            if (count($this->values) > 1) {
                throw new DatabaseException('Ignoring additional values after select for insert statement');
            }

            $placeholders = " {$this->values[0]}";
        } elseif (is_array($this->values[0])) {
            // FIXME this plug to use a loop instead of str_rep.
            $plug = substr(str_repeat('?, ', count($this->values[0])), 0, -2);
            $placeholders = " VALUES ({$plug})";

            for ($i = 1; $i < $size; $i++) {
                if (!is_array($this->values[$i])) {
                    throw new DatabaseException('Invalid nested value for insert statement');
                }

                if (count($this->values[0]) != count($this->values[$i])) {
                    throw new DatabaseException('Invalid nested value count for insert statement');
                }

                $plug = substr(str_repeat('?, ', count($this->values[$i])), 0, -2);
                $placeholders .= ", ({$plug})";
            }
        } else {
            if ($this->values[0] instanceof Raw) {
                $plug = "{$this->values[0]}";
            } elseif (is_scalar($this->values[0])) {
                $plug = '?';
            } else {
                throw new DatabaseException('Invalid value for insert statement');
            }

            for ($i = 1; $i < $size; $i++) {
                if ($this->values[$i] instanceof Raw) {
                    $plug .= ", {$this->values[$i]}";
                } elseif (is_scalar($this->values[$i])) {
                    $plug .= ', ?';
                } else {
                    throw new DatabaseException('Invalid value for insert statement');
                }
            }

            $placeholders = " VALUES ({$plug})";
        }

        $sql = 'INSERT';
        if ($this->ignore) {
            $sql .= ' IGNORE';
        }
        $sql .= " INTO {$this->table}";
        if (!empty($this->columns)) {
            $sql .= ' (' . implode(', ', $this->columns) . ')';
        }
        $sql .= "{$placeholders}";

        return $sql;
    }

    /**
     * @return array<int, mixed>
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

        return $values;
    }

    /**
     * @return int|string
     * @throws DatabaseException
     */
    public function execute()
    {
        parent::execute();

        return $this->dbh->lastInsertId();
    }
}
