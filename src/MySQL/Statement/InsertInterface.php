<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\MySQL\Clause\RawInterface;

interface InsertInterface extends QueryBuilder\StatementInterface
{
    /**
     * @param string $level
     *
     * @return self
     */
    public function priority(string $level): self;

    /**
     * @return self
     */
    public function ignore(): self;

    /**
     * @param string $table
     *
     * @return self
     */
    public function into(string $table): self;

    /**
     * @param string ...$columns
     *
     * @return self
     */
    public function columns(string ...$columns): self;

    /**
     * @param float|int|string|RawInterface|SelectInterface $value
     * @param float|int|string|RawInterface                 ...$values
     *
     * @return self
     */
    public function values($value, ...$values): self;

    /**
     * @param array<string, float|int|string|RawInterface> $paris
     *
     * @return self
     */
    public function onDuplicateUpdate(array $paris = []): self;
}
