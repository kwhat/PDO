<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder;

use PDO;
use PDOException;
use PDOStatement;

abstract class AbstractStatement implements QueryInterface
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
     * @return PDOStatement
     */
    public function execute(): PDOStatement
    {
        // FIXME What happens if this returns false?
        $stmt = $this->dbh->prepare($this->__toString());
        $stmt->execute($this->getValues());

        return $stmt;
    }
}
