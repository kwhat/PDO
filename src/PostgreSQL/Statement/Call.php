<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\PostgreSQL\Statement;

use FaaPz\PDO\QueryBuilder\MySQL;
use FaaPz\PDO\QueryBuilder\PostgreSQL;
use PDO;

/**
 * @phan-file-suppress PhanParamSignatureMismatch
 * @phan-file-suppress PhanParamSignaturePHPDocMismatchParamType, PhanParamSignaturePHPDocMismatchReturnType
 *
 * @property PostgreSQL\Clause\Method|null $method
 *
 * @method __construct(PDO $dbh, ?PostgreSQL\Clause\Method $procedure = null)
 * @method self method(PostgreSQL\Clause\Method $procedure)
 */
class Call extends MySQL\Statement\Call
{

}
