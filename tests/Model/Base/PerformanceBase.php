<?php

declare(strict_types=1);

namespace Imi\Pgsql\Test\Model\Base;

use Imi\Pgsql\Model\PgModel as Model;

/**
 * tb_performance 基类.
 *
 * 此文件是自动生成，请勿手动修改此文件！
 *
 * @property int|null    $id
 * @property string|null $value
 */
#[
    \Imi\Model\Annotation\Entity(),
    \Imi\Model\Annotation\Table(name: 'tb_performance', id: [
        'id',
    ])
]
abstract class PerformanceBase extends Model
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
     */
    #[
        \Imi\Model\Annotation\Column(name: 'id', type: 'int4', nullable: false, isPrimaryKey: true, primaryKeyIndex: 0, isAutoIncrement: true)
    ]
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
    public function setId(mixed $id): self
    {
        $this->id = null === $id ? null : (int) $id;

        return $this;
    }

    /**
     * value.
     */
    #[
        \Imi\Model\Annotation\Column(name: 'value', type: 'varchar', length: 255, nullable: false)
    ]
    protected ?string $value = null;

    /**
     * 获取 value.
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * 赋值 value.
     *
     * @param string|null $value value
     *
     * @return static
     */
    public function setValue(mixed $value): self
    {
        if (\is_string($value) && mb_strlen($value) > 255)
        {
            throw new \InvalidArgumentException('The maximum length of $value is 255');
        }
        $this->value = null === $value ? null : $value;

        return $this;
    }
}
