<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\QueryBuilder\PostgreSQL\Statement;

use FaaPz\PDO\QueryBuilder\MySQL;
use FaaPz\PDO\QueryBuilder\PostgreSQL;

/**
 * @property string|array<string, string|Call|Select>|null $table
 * @property array<int|string, string> $columns
 * @property array<int, Call|Select> $union
 * @property array<int, string> $groupBy
 * @property PostgreSQL\Clause\Conditional|null $having
 *
 * @property PostgreSQL\Clause\Join[] $join
 * @property PostgreSQL\Clause\Conditional $where
 */
class Delete extends MySQL\Statement\Delete
{

}
