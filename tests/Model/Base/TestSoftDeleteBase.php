<?php

declare(strict_types=1);

namespace Imi\Pgsql\Test\Model\Base;

use Imi\Pgsql\Model\PgModel as Model;

/**
 * tb_test_soft_delete 基类.
 *
 * 此文件是自动生成，请勿手动修改此文件！
 *
 * @property int|null    $id
 * @property string|null $title
 * @property int|null    $deleteTime
 */
#[
    \Imi\Model\Annotation\Entity(),
    \Imi\Model\Annotation\Table(name: 'tb_test_soft_delete', id: [
        'id',
    ])
]
abstract class TestSoftDeleteBase extends Model
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
     * title.
     */
    #[
        \Imi\Model\Annotation\Column(name: 'title', type: 'varchar', length: 255, nullable: false)
    ]
    protected ?string $title = null;

    /**
     * 获取 title.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * 赋值 title.
     *
     * @param string|null $title title
     *
     * @return static
     */
    public function setTitle(mixed $title): self
    {
        if (\is_string($title) && mb_strlen($title) > 255)
        {
            throw new \InvalidArgumentException('The maximum length of $title is 255');
        }
        $this->title = null === $title ? null : $title;

        return $this;
    }

    /**
     * delete_time.
     */
    #[
        \Imi\Model\Annotation\Column(name: 'delete_time', type: 'int4', nullable: false, default: '0')
    ]
    protected ?int $deleteTime = 0;

    /**
     * 获取 deleteTime.
     */
    public function getDeleteTime(): ?int
    {
        return $this->deleteTime;
    }

    /**
     * 赋值 deleteTime.
     *
     * @param int|null $deleteTime delete_time
     *
     * @return static
     */
    public function setDeleteTime(mixed $deleteTime): self
    {
        $this->deleteTime = null === $deleteTime ? null : (int) $deleteTime;

        return $this;
    }
}
