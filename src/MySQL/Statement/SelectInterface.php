<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\MySQL\StatementInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\ConditionalInterface;

interface SelectInterface extends StatementInterface
{
    /**
     * @return self
     */
    public function distinct(): self;

    /**
     * @param array<int|string, string|SelectInterface> $columns
     *
     * @return self
     */
    public function columns(array $columns = ['*']): self;

    /**
     * @param string|array<string, string|SelectInterface> $table
     *
     * @return self
     */
    public function from($table): self;

    /**
     * @param SelectInterface $query
     *
     * @return self
     */
    public function union(SelectInterface $query): self;

    /**
     * @param SelectInterface $query
     *
     * @return self
     */
    public function unionAll(SelectInterface $query): self;

    /**
     * @param string ...$columns
     *
     * @return self
     */
    public function groupBy(string ...$columns): self;

    /**
     * @param ConditionalInterface $clause
     *
     * @return self
     */
    public function having(ConditionalInterface $clause): self;
}
