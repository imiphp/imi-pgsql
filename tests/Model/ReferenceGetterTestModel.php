<?php

declare(strict_types=1);

namespace Imi\Pgsql\Test\Model;

use Imi\Model\Annotation\Column;
use Imi\Model\Annotation\Entity;
use Imi\Pgsql\Test\Model\Base\TreeBase;

/**
 * @Entity
 *
 * @property int[] $list
 */
class ReferenceGetterTestModel extends TreeBase
{
    /**
     * @Column(virtual=true)
     *
     * @var int[]
     */
    protected array $list = [];

    /**
     * @return int[]
     */
    public function &getList(): array
    {
        return $this->list;
    }

    /**
     * @param int[] $list
     *
     * @return self
     */
    public function setList(array $list)
    {
        $this->list = $list;

        return $this;
    }
}
