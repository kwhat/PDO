<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv\Clause;

use FaaPz\PDO\QueryBuilder\QueryInterface;

class Method implements MethodInterface
{
    /** @var string $name */
    protected string $name;

    /** @var array<mixed> $values */
    protected array $values;

    /**
     * @param string $name
     * @param mixed  ...$args
     */
    public function __construct(string $name, ...$args)
    {
        $this->name = $name;
        $this->values = $args;
    }

    /**
     * @return array<mixed>
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->values as $value) {
            if ($value instanceof QueryInterface) {
                $values = array_merge($values, $value->getValues());
            } else {
                $values[] = $value;
            }
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $placeholders = '';
        foreach ($this->values as $value) {
            if (!empty($placeholders)) {
                $placeholders .= ', ';
            }

            if ($value instanceof QueryInterface) {
                $placeholders .= "{$value}";
            } else {
                $placeholders .= '?';
            }
        }

        return "{$this->name}({$placeholders})";
    }
}
