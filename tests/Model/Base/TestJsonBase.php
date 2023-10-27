<?php

declare(strict_types=1);

namespace Imi\Pgsql\Test\Model\Base;

use Imi\Pgsql\Model\PgModel as Model;

/**
 * test 基类.
 *
 * 此文件是自动生成，请勿手动修改此文件！
 *
 * @property int|null                             $id
 * @property \Imi\Util\LazyArrayObject|array|null $jsonData json数据
 */
#[
    \Imi\Model\Annotation\Entity(),
    \Imi\Model\Annotation\Table(name: 'tb_test_json', id: [
        'id',
    ])
]
abstract class TestJsonBase extends Model
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
     * json数据.
     * json_data.
     *
     * @var \Imi\Util\LazyArrayObject|array|null
     */
    #[
        \Imi\Model\Annotation\Column(name: 'json_data', type: 'json', nullable: false)
    ]
    protected $jsonData = null;

    /**
     * 获取 jsonData - json数据.
     *
     * @return \Imi\Util\LazyArrayObject|array|null
     */
    public function &getJsonData()
    {
        return $this->jsonData;
    }

    /**
     * 赋值 jsonData - json数据.
     *
     * @param \Imi\Util\LazyArrayObject|array|null $jsonData json_data
     *
     * @return static
     */
    public function setJsonData(mixed $jsonData): self
    {
        $this->jsonData = null === $jsonData ? null : $jsonData;

        return $this;
    }
}
