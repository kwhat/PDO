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
 * @property MySQL\Clause\Conditional|null $having
 *
 * @method self join(MySQL\Clause\Join $clause)
 * @method self where(MySQL\Clause\Conditional $clause)
 * @method self having(MySQL\Clause\Conditional $clause)
 */
class Select extends Ansi\Statement\Select
{
    /** @var array<int, MySQL\Statement\Call|self> $union */
    protected $union = [];

    /** @var array<int, MySQL\Statement\Call|self> $unionAll */
    protected $unionAll = [];

    /** @var MySQL\Clause\Limit|null $limit */
    protected $limit = null;

    protected function getUnionCount(): int
    {
        return count($this->union) + count($this->unionAll);
    }

    /**
     * @param self $query
     *
     * @return $this
     */
    public function union(self $query): self
    {
        $this->union[$this->getUnionCount()] = $query;

        return $this;
    }

    protected function renderUnion(): string
    {
        $sql = '';
        for ($i = 0; $i < $this->getUnionCount(); $i++) {
            if (isset($this->union[$i])) {
                $union = "{$this->union[$i]}";
            } elseif (isset($this->unionAll[$i])) {
                $union = "ALL {$this->unionAll[$i]}";
            } else {
                trigger_error('Union offset mismatch', E_USER_ERROR);
            }

            $sql .= " UNION {$union}";
        }

        return $sql;
    }

    /**
     * @param self $query
     *
     * @return $this
     */
    public function unionAll(self $query): self
    {
        $this->unionAll[$this->getUnionCount()] = $query;

        return $this;
    }

    /**
     * @param MySQL\Clause\Limit|null $limit
     *
     * @return $this
     */
    public function limit(?MySQL\Clause\Limit $limit = null)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderLimit(): string
    {
        $sql = '';
        if ($this->limit != null) {
            $sql = " {$this->limit}";
        }

        return $sql;
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
        return 'SELECT'
            . $this->renderDistinct()
            . $this->renderColumns()
            . $this->renderFrom()
            . $this->renderJoin()
            . $this->renderWhere()
            . $this->renderGroupBy()
            . $this->renderHaving()
            . $this->renderOrderBy()
            . $this->renderLimit()
            . $this->renderUnion();
    }
}
