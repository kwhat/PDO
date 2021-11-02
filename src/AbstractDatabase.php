<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder;

use PDO;

abstract class AbstractDatabase extends PDO implements DatabaseInterface
{
    /**
     * @param string            $dsn
     * @param ?string           $username
     * @param ?string           $password
     * @param array<int, mixed> $options
     *
     * @codeCoverageIgnore
     */
    public function __construct(string $dsn, ?string $username = null, ?string $password = null, array $options = [])
    {
        parent::__construct($dsn, $username, $password, $options + $this->getDefaultOptions());
    }

    /**
     * @return array<int, mixed>
     *
     * @codeCoverageIgnore
     */
    protected function getDefaultOptions(): array
    {
        return [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
    }
}
