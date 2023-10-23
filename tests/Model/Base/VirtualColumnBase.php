<?php

declare(strict_types=1);

namespace Imi\Pgsql\Test\Model\Base;

use Imi\Model\Annotation\Column;
use Imi\Model\Annotation\Entity;
use Imi\Model\Annotation\Table;
use Imi\Pgsql\Model\PgModel as Model;

/**
 * tb_virtual_column 基类.
 *
 * @Entity(camel=true, bean=true, incrUpdate=false)
 *
 * @Table(name="tb_virtual_column", usePrefix=false, id={"id"}, dbPoolName=null)
 *
 * @property int|null              $id
 * @property int|null              $amount
 * @property string|float|int|null $virtualAmount
 */
abstract class VirtualColumnBase extends Model
{
    /**
     * {@inheritdoc}
     */
    public const PRIMARY_KEY = 'id';

    /**
     * {@inheritdoc}
     */
    public const PRIMARY_KEYS = ['id'];

    /**
     * id.
     *
     * @Column(name="id", type="int8", length=-1, accuracy=0, nullable=false, default="", isPrimaryKey=true, primaryKeyIndex=0, isAutoIncrement=true, ndims=0, virtual=false)
     */
    protected ?int $id = null;

    /**
     * 获取 id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * 赋值 id.
     *
     * @param int|null $id id
     *
     * @return static
     */
    public function setId($id)
    {
        $this->id = null === $id ? null : (int) $id;

        return $this;
    }

    /**
     * amount.
     *
     * @Column(name="amount", type="int4", length=-1, accuracy=0, nullable=false, default="", isPrimaryKey=false, primaryKeyIndex=-1, isAutoIncrement=false, ndims=0, virtual=false)
     */
    protected ?int $amount = null;

    /**
     * 获取 amount.
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * 赋值 amount.
     *
     * @param int|null $amount amount
     *
     * @return static
     */
    public function setAmount($amount)
    {
        $this->amount = null === $amount ? null : (int) $amount;

        return $this;
    }

    /**
     * virtual_amount.
     *
     * @Column(name="virtual_amount", type="numeric", length=10, accuracy=2, nullable=false, default="((amount)::numeric / (100)::numeric)", isPrimaryKey=false, primaryKeyIndex=-1, isAutoIncrement=false, ndims=0, virtual=true)
     */
    protected string|float|int|null $virtualAmount = null;

    /**
     * 获取 virtualAmount.
     */
    public function getVirtualAmount(): string|float|int|null
    {
        return $this->virtualAmount;
    }

    /**
     * 赋值 virtualAmount.
     *
     * @param string|float|int|null $virtualAmount virtual_amount
     *
     * @return static
     */
    public function setVirtualAmount($virtualAmount)
    {
        $this->virtualAmount = null === $virtualAmount ? null : $virtualAmount;

        return $this;
    }
}
