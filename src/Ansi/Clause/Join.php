<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\Ansi\Clause;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\Ansi;

class Join implements QueryBuilder\QueryInterface
{
    /** @var string|array<string,string|Ansi\Statement\Select> $subject */
    protected $subject;

    /** @var Ansi\Clause\Conditional $on */
    protected $on;

    /** @var string $type */
    protected $type;

    /**
     * @param string|array<string,string|Ansi\Statement\Select> $subject
     * @param Ansi\Clause\Conditional                           $on
     * @param string                                            $type
     */
    public function __construct($subject, Ansi\Clause\Conditional $on, string $type = '')
    {
        $this->subject = $subject;
        $this->on = $on;
        $this->type = strtoupper(trim($type));
    }

    /**
     * @return mixed[]
     */
    public function getValues(): array
    {
        return $this->on->getValues();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $table = $this->subject;
        if (is_array($this->subject)) {
            reset($this->subject);
            $alias = key($this->subject);
            if (!is_string($alias)) {
                trigger_error('Invalid subject array, use string keys for alias', E_USER_ERROR);
            }

            $table = $this->subject[$alias];
            if ($table instanceof Ansi\Statement\Select) {
                $table = "({$table})";
            }
            $table .= " AS {$alias}";
        } elseif (!is_string($this->subject)) {
            trigger_error('Invalid subject value, use array with string key for alias', E_USER_ERROR);
        }

        $sql = "JOIN {$table} ON {$this->on}";
        if (!empty($this->type)) {
            $sql = "{$this->type} {$sql}";
        }

        return $sql;
    }
}
