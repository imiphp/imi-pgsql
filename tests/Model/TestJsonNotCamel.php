<?php

declare(strict_types=1);

namespace Imi\Pgsql\Test\Model;

use Imi\Bean\Annotation\Inherit;
use Imi\Model\Annotation\Entity;
use Imi\Pgsql\Test\Model\Base\TestJsonBase;

/**
 * tb_test_json.
 *
 * @property \Imi\Util\LazyArrayObject|array $jsonData json数据
 */
#[Inherit]
#[Entity(camel: false)]
class TestJsonNotCamel extends TestJsonBase
{
}
