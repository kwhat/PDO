<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\Ansi;
use FaaPz\PDO\QueryBuilder\MySQL;

/**
 * @phan-file-suppress PhanParamSignatureMismatch
 * @phan-file-suppress PhanParamSignaturePHPDocMismatchParamType, PhanParamSignaturePHPDocMismatchReturnType
 *
 * @property MySQL\Clause\Join[] $join
 * @property MySQL\Clause\Conditional|null $where
 *
 * @method self join(MySQL\Clause\Join $clause)
 * @method self where(MySQL\Clause\Conditional $clause)
 */
class Delete extends Ansi\Statement\Delete
{
    /** @var MySQL\Clause\Limit|null $limit */
    protected $limit = null;

    /**
     * @param MySQL\Clause\Limit|null $limit
     *
     * @return $this
     */
    public function limit(?MySQL\Clause\Limit $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function getValues(): array
    {
        $values = parent::getValues();
        if ($this->limit != null) {
            $values = array_merge($values, $this->limit->getValues());
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = parent::__toString();
        if ($this->limit != null) {
            $sql .= " {$this->limit}";
        }

        return $sql;
    }
}
