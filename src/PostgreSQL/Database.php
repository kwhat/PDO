<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\PostgreSQL;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\PostgreSQL;

class Database extends QueryBuilder\AbstractDatabase
{
    /**
     * @param PostgreSQL\Clause\Method|null $procedure
     *
     * @return PostgreSQL\Statement\Call
     */
    public function call(?PostgreSQL\Clause\Method $procedure = null): PostgreSQL\Statement\Call
    {
        return new PostgreSQL\Statement\Call($this, $procedure);
    }

    /**
     * @param array<int|string, mixed> $pairs
     *
     * @return PostgreSQL\Statement\Insert
     */
    public function insert(array $pairs = []): PostgreSQL\Statement\Insert
    {
        return new PostgreSQL\Statement\Insert($this, $pairs);
    }

    /**
     * @param array<int|string, string> $columns
     *
     * @return PostgreSQL\Statement\Select
     */
    public function select(array $columns = ['*']): PostgreSQL\Statement\Select
    {
        return new PostgreSQL\Statement\Select($this, $columns);
    }

    /**
     * @param array<string, mixed> $pairs
     *
     * @return PostgreSQL\Statement\Update
     */
    public function update(array $pairs = []): PostgreSQL\Statement\Update
    {
        return new PostgreSQL\Statement\Update($this, $pairs);
    }

    /**
     * @param string|array<string, string> $table
     *
     * @return PostgreSQL\Statement\Delete
     */
    public function delete($table = null): PostgreSQL\Statement\Delete
    {
        return new PostgreSQL\Statement\Delete($this, $table);
    }
}
