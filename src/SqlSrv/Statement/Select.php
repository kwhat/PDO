<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv\Statement;

use FaaPz\PDO\QueryBuilder\MySQL;
use FaaPz\PDO\QueryBuilder\SqlSrv;

/**
 * @property string|array<string, string|Exec|Select>|null $table
 * @property array<int, Exec|Select> $union
 * @property array<int, Exec|Select> $unionAll
 */
class Select extends MySQL\Statement\Select
{
    /** @var SqlSrv\Clause\Top|null $top */
    protected $top = null;

    /**
     * @param SqlSrv\Clause\Top|null $limit
     *
     * @return self
     */
    public function top(?SqlSrv\Clause\Top $limit): self
    {
        $this->top = $limit;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = 'SELECT'
            . $this->renderDistinct();

        if ($this->top != null) {
            $sql .= " {$this->top}";
        }

        $sql .= $this->renderColumns()
            . $this->renderFrom()
            . $this->renderJoin()
            . $this->renderWhere()
            . $this->renderGroupBy()
            . $this->renderHaving()
            . $this->renderOrderBy()
            . $this->renderUnion();

        return $sql;
    }
}
