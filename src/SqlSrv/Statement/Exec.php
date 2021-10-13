<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\SqlSrv\Statement;

use FaaPz\PDO\QueryBuilder\SqlSrv\AbstractStatement;
use FaaPz\PDO\QueryBuilder\SqlSrv\Clause\Method;
use PDO;

class Exec extends AbstractStatement
{
    /** @var ?Method $method */
    protected ?Method $method = null;

    /**
     * @param PDO     $dbh
     * @param ?Method $procedure
     */
    public function __construct(PDO $dbh, ?Method $procedure = null)
    {
        parent::__construct($dbh);
        if ($procedure != null) {
            $this->method($procedure);
        }
    }

    /**
     * @param Method $procedure
     *
     * @return self
     */
    public function method(Method $procedure): self
    {
        $this->method = $procedure;

        return $this;
    }

    /**
     * @return string
     */
    protected function renderMethod(): string
    {
        if ($this->method == null) {
            trigger_error('No method set for call statement', E_USER_ERROR);
        }

        return " {$this->method}";
    }

    /**
     * @return array<mixed>
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
        return 'EXEC' . $this->renderMethod();
    }
}
