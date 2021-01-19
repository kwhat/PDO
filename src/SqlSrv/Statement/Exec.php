<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv\Statement;

use FaaPz\PDO\QueryBuilder\MySQL;

class Exec extends MySQL\Statement\Call
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'EXEC'
            . $this->renderMethod();
    }
}
