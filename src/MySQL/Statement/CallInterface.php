<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\MySQL\Clause\MethodInterface;
use FaaPz\PDO\QueryBuilder\QueryInterface;

interface CallInterface extends QueryInterface
{
    /**
     * @param MethodInterface $procedure
     *
     * @return self
     */
    public function method(MethodInterface $procedure): self;
}
