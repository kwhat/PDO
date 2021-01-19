<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\SqlSrv;

class Database extends QueryBuilder\AbstractDatabase
{
    /**
     * @param SqlSrv\Clause\Method|null $procedure
     *
     * @return SqlSrv\Statement\Exec
     */
    public function execute(?SqlSrv\Clause\Method $procedure = null): SqlSrv\Statement\Exec
    {
        return new SqlSrv\Statement\Exec($this, $procedure);
    }

    /**
     * @param array<int|string, mixed> $pairs
     *
     * @return SqlSrv\Statement\Insert
     */
    public function insert(array $pairs = []): SqlSrv\Statement\Insert
    {
        return new SqlSrv\Statement\Insert($this, $pairs);
    }

    /**
     * @param string|array<string, string> $table
     *
     * @return SqlSrv\Statement\Delete
     */
    public function delete($table = null): SqlSrv\Statement\Delete
    {
        return new SqlSrv\Statement\Delete($this, $table);
    }

    /**
     * @param array<int|string, string> $columns
     *
     * @return SqlSrv\Statement\Select
     */
    public function select(array $columns = ['*']): SqlSrv\Statement\Select
    {
        return new SqlSrv\Statement\Select($this, $columns);
    }

    /**
     * @param array<string, mixed> $pairs
     *
     * @return SqlSrv\Statement\Update
     */
    public function update(array $pairs = []): SqlSrv\Statement\Update
    {
        return new SqlSrv\Statement\Update($this, $pairs);
    }
}
