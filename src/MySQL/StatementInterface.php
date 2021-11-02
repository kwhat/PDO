<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\ConditionalInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\JoinInterface;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\LimitInterface;

interface StatementInterface extends QueryBuilder\StatementInterface
{
    /**
     * @param JoinInterface $clause
     *
     * @return self
     */
    public function join(JoinInterface $clause): self;

    /**
     * @param ConditionalInterface $clause
     *
     * @return self
     */
    public function where(ConditionalInterface $clause): self;

    /**
     * @param string $column
     * @param string $direction
     *
     * @return self
     */
    public function orderBy(string $column, string $direction = ''): self;

    /**
     * @param LimitInterface $limit
     *
     * @return self
     */
    public function limit(LimitInterface $limit): self;
}
