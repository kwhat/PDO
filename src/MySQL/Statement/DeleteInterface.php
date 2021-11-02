<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\MySQL\StatementInterface;

interface DeleteInterface extends StatementInterface
{
    /**
     * @param string|array<string, string> $table
     *
     * @return self
     */
    public function from($table): self;
}
