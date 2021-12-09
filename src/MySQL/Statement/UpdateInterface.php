<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\QueryInterface;

interface UpdateInterface extends QueryInterface
{
    /**
     * @param string $table
     *
     * @return self
     */
    public function table(string $table): self;

    /**
     * @param string $column
     * @param mixed  $value
     *
     * @return self
     */
    public function set(string $column, $value): self;

    /**
     * @param array<string, mixed> $pairs
     *
     * @return self
     */
    public function pairs(array $pairs): self;
}
