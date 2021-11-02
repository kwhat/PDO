<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\QueryInterface;

interface ViewInterface extends QueryInterface
{
    /**
     * @param SelectInterface $query
     *
     * @return self
     */
    public function statement(SelectInterface $query): self;

    /**
     * @return self
     */
    public function orReplace(): self;

    /**
     * @return self
     */
    public function algorithm(): self;
}
