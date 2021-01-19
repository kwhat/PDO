<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Ansi\Clause;

use FaaPz\PDO\QueryBuilder\Ansi;

class Grouping extends Ansi\Clause\Conditional
{
    /** @var Ansi\Clause\Conditional[] $value */
    protected $value;

    /**
     * @param string                  $rule
     * @param Ansi\Clause\Conditional $clause
     * @param Ansi\Clause\Conditional ...$clauses
     */
    public function __construct(string $rule, Ansi\Clause\Conditional $clause, Ansi\Clause\Conditional ...$clauses)
    {
        array_unshift($clauses, $clause);
        parent::__construct('', $rule, $clauses);
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->value as $clause) {
            $values = array_merge($values, $clause->getValues());
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = '';
        foreach ($this->value as $clause) {
            if (!empty($sql)) {
                $sql .= " {$this->operator} ";
            }

            if ($clause instanceof self) {
                $sql .= "({$clause})";
            } else {
                $sql .= "{$clause}";
            }
        }

        return $sql;
    }
}
