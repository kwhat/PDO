<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Ansi;

use FaaPz\PDO\QueryBuilder\AbstractDatabase;
use FaaPz\PDO\QueryBuilder\Ansi;

class Database extends AbstractDatabase
{
    /**
     * @param array<int|string, mixed> $pairs
     *
     * @return Ansi\Statement\Insert
     */
    public function insert(array $pairs = []): Ansi\Statement\Insert
    {
        return new Ansi\Statement\Insert($this, $pairs);
    }

    /**
     * @param array<int|string, string> $columns
     *
     * @return Ansi\Statement\Select
     */
    public function select(array $columns = ['*']): Ansi\Statement\Select
    {
        return new Ansi\Statement\Select($this, $columns);
    }

    /**
     * @param array<string, mixed> $pairs
     *
     * @return Ansi\Statement\Update
     */
    public function update(array $pairs = []): Ansi\Statement\Update
    {
        return new Ansi\Statement\Update($this, $pairs);
    }

    /**
     * @param string|array<string, string>|null $table
     *
     * @return Ansi\Statement\Delete
     */
    public function delete($table = null): Ansi\Statement\Delete
    {
        return new Ansi\Statement\Delete($this, $table);
    }
}
