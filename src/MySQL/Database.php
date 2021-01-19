<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\MySQL;

class Database extends QueryBuilder\AbstractDatabase
{
    /**
     * @param MySQL\Clause\Method|null $procedure
     *
     * @return MySQL\Statement\Call
     */
    public function call(?MySQL\Clause\Method $procedure = null): MySQL\Statement\Call
    {
        return new MySQL\Statement\Call($this, $procedure);
    }

    /**
     * @param array<int|string, mixed> $pairs
     *
     * @return MySQL\Statement\Insert
     */
    public function insert(array $pairs = []): MySQL\Statement\Insert
    {
        return new MySQL\Statement\Insert($this, $pairs);
    }

    /**
     * @param array<int|string, string> $columns
     *
     * @return MySQL\Statement\Select
     */
    public function select(array $columns = ['*']): MySQL\Statement\Select
    {
        return new MySQL\Statement\Select($this, $columns);
    }

    /**
     * @param array<string, mixed> $pairs
     *
     * @return MySQL\Statement\Update
     */
    public function update(array $pairs = []): MySQL\Statement\Update
    {
        return new MySQL\Statement\Update($this, $pairs);
    }

    /**
     * @param string|array<string, string> $table
     *
     * @return MySQL\Statement\Delete
     */
    public function delete($table = null): MySQL\Statement\Delete
    {
        return new MySQL\Statement\Delete($this, $table);
    }
}
