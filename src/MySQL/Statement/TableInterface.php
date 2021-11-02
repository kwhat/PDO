<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\QueryInterface;

interface TableInterface extends QueryInterface
{
    /**
     * @param string $name
     *
     * @return self
     */
    public function table(string $name): self;

    /**
     * @param string $type
     *
     * @return self
     */
    public function type(string $type): self;

    /**
     * @param string $value
     *
     * @return self
     */
    public function default(string $value): self;

    /**
     * @return self
     */
    public function autoIncrement(): self;

    /**
     * @return self
     */
    public function notNull(): self;

    /**
     * @return self
     */
    public function primaryKey(): self;

    /**
     * @return self
     */
    public function unique(): self;
}
