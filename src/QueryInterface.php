<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder;

interface QueryInterface
{
    /**
     * @return array
     */
    public function getValues(): array;

    /**
     * @return string
     */
    public function __toString(): string;
}
