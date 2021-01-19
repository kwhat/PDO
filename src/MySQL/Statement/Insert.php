<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder\Ansi;
use FaaPz\PDO\QueryBuilder\QueryInterface;

class Insert extends Ansi\Statement\Insert
{
    /** @var bool $ignore */
    protected $ignore = false;

    /** @var array<string, mixed> $update */
    protected $update = [];

    /**
     * @return $this
     */
    public function ignore(): self
    {
        $this->ignore = true;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderIgnore(): string
    {
        $sql = '';
        if ($this->ignore) {
            $sql = ' IGNORE';
        }

        return $sql;
    }

    /**
     * @param array<string, mixed> $paris
     *
     * @return $this
     */
    public function onDuplicateUpdate(array $paris = []): self
    {
        $this->update = $paris;

        return $this;
    }


    protected function renderOnDuplicateUpdate(): string
    {
        $sql = '';
        if (!empty($this->update)) {
            $sql = ' ON DUPLICATE KEY UPDATE';
            foreach ($this->update as $column => $value) {
                if (!$value instanceof QueryInterface) {
                    $value = '?';
                }

                $sql .= " {$column} = {$value}, ";
            }
            $sql = substr($sql, 0, -2);
        }

        return $sql;
    }

    public function getValues(): array
    {
        $values = parent::getValues();

        foreach ($this->update as $value) {
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
        return 'INSERT'
            . $this->renderIgnore()
            . $this->renderInto()
            . $this->renderColumns()
            . $this->renderValues()
            . $this->renderOnDuplicateUpdate();
    }
}
