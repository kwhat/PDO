<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder;

use PDO;
use PDOException;
use PDOStatement;

abstract class AbstractStatement implements StatementInterface
{
    /** @var PDO $dbh */
    protected PDO $dbh;


    /**
     * @param PDO $dbh
     */
    public function __construct(PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * @throws PDOException
     *
     * @return PDOStatement|false
     */
    public function execute()
    {
        $stmt = $this->dbh->prepare($this->__toString());
        if ($stmt !== false) {
            $stmt->execute($this->getValues());
        }

        return $stmt;
    }
}
