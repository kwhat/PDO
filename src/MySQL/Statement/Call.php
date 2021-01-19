<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\MySQL\Statement;

use FaaPz\PDO\QueryBuilder;
use FaaPz\PDO\QueryBuilder\MySQL;
use PDO;

class Call extends QueryBuilder\AbstractStatement
{
    /** @var MySQL\Clause\Method|null $method */
    protected $method = null;

    /**
     * @param PDO                      $dbh
     * @param MySQL\Clause\Method|null $procedure
     */
    public function __construct(PDO $dbh, ?MySQL\Clause\Method $procedure = null)
    {
        parent::__construct($dbh);

        if ($procedure != null) {
            $this->method($procedure);
        }
    }

    /**
     * @param MySQL\Clause\Method $procedure
     *
     * @return $this
     */
    public function method(MySQL\Clause\Method $procedure): self
    {
        $this->method = $procedure;

        return $this;
    }

    protected function renderMethod(): string
    {
        if ($this->method == null) {
            trigger_error('No method set for call statement', E_USER_ERROR);
        }

        return " {$this->method}";
    }

    /**
     * @return mixed[]
     */
    public function getValues(): array
    {
        return $this->method->getValues();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'CALL'
            . $this->renderMethod();
    }
}
