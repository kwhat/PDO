<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder;

use PDOException;
use PDOStatement;

interface StatementInterface extends QueryInterface
{
    /**
     * @return array
     */
    public function getValues(): array;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @throws PDOException
     *
     * @return PDOStatement|false
     */
    public function execute();
}
