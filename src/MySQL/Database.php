<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\Method;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\Call;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\CallInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\Delete;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\DeleteInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\Insert;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\InsertInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\Select;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\SelectInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\Update;
use FaaPz\PDO\QueryBuilder\MySQL\Statement\UpdateInterface;

class Database extends QueryBuilder\AbstractDatabase
{
    /**
     * @param ?Method $procedure
     *
     * @return CallInterface
     */
    public function call(?Method $procedure = null): CallInterface
    {
        return new Call($this, $procedure);
    }

    /**
     * @param array<int|string, mixed> $pairs
     *
     * @return InsertInterface
     */
    public function insert(array $pairs = []): InsertInterface
    {
        return new Insert($this, $pairs);
    }

    /**
     * @param array<int|string, string> $columns
     *
     * @return SelectInterface
     */
    public function select(array $columns = ['*']): SelectInterface
    {
        return new Select($this, $columns);
    }

    /**
     * @param array<string, mixed> $pairs
     *
     * @return UpdateInterface
     */
    public function update(array $pairs = []): UpdateInterface
    {
        return new Update($this, $pairs);
    }

    /**
     * @param ?string|?array<string, string> $table
     *
     * @return DeleteInterface
     */
    public function delete($table = null): DeleteInterface
    {
        return new Delete($this, $table);
    }

    /**
     * @param ?string $name
     *
     * @return TableInterface
     */
    public function table(?string $name = null): TableInterface
    {
        return new Table($this, $name);
    }

    /**
     * @param ?SelectInterface $query
     *
     * @return ViewInterface
     */
    public function view(?SelectInterface $query = null): ViewInterface
    {
        return new View($this, $query);
    }
}
