<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\Ansi;
use FaaPz\PDO\QueryBuilder\MySQL;

class Update extends Ansi\Statement\Update
{
    /** @var MySQL\Clause\Limit|null $limit */
    protected $limit = null;

    /**
     * @param MySQL\Clause\Limit|null $limit
     *
     * @return $this
     */
    public function limit(?MySQL\Clause\Limit $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    protected function renderLimit(): string
    {
        $sql = '';
        if ($this->limit != null) {
            $sql = " LIMIT {$this->limit}";
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
        return parent::__toString()
            . $this->renderLimit();
    }
}
