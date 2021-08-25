<?php

declare(strict_types=1);

namespace Imi\Pgsql\Db;

use Imi\Db\Drivers\BaseStatement;
use Imi\Pgsql\Db\Contract\IPgsqlStatement;

abstract class PgsqlBaseStatement extends BaseStatement implements IPgsqlStatement
{
}
