<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SQLite;

use FaaPz\PDO\QueryBuilder\AbstractDatabase;
use FaaPz\PDO\QueryBuilder\SQLite;

class Database extends AbstractDatabase
{
    /**
     * @param array<int|string, mixed> $pairs
     *
     * @return SQLite\Statement\Insert
     */
    public function insert(array $pairs = []): SQLite\Statement\Insert
    {
        return new SQLite\Statement\Insert($this, $pairs);
    }

    /**
     * @param array<int|string, string> $columns
     *
     * @return SQLite\Statement\Select
     */
    public function select(array $columns = ['*']): SQLite\Statement\Select
    {
        return new SQLite\Statement\Select($this, $columns);
    }

    /**
     * @param array<string, mixed> $pairs
     *
     * @return SQLite\Statement\Update
     */
    public function update(array $pairs = []): SQLite\Statement\Update
    {
        return new SQLite\Statement\Update($this, $pairs);
    }

    /**
     * @param string|array<string, string>|null $table
     *
     * @return SQLite\Statement\Delete
     */
    public function delete($table = null): SQLite\Statement\Delete
    {
        return new SQLite\Statement\Delete($this, $table);
    }
}
