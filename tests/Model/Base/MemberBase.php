<?php

declare(strict_types=1);

namespace Imi\Pgsql\Test\Model\Base;

use Imi\Pgsql\Model\PgModel as Model;

/**
 * tb_member 基类.
 *
 * 此文件是自动生成，请勿手动修改此文件！
 *
 * @property int|null    $id
 * @property string|null $username 用户名
 * @property string|null $password 密码
 */
#[
    \Imi\Model\Annotation\Entity(),
    \Imi\Model\Annotation\Table(name: 'tb_member', id: [
        'id',
    ])
]
abstract class MemberBase extends Model
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
     * 用户名.
     * username.
     */
    #[
        \Imi\Model\Annotation\Column(name: 'username', type: 'varchar', length: 32, nullable: false, default: '\'\'::character varying')
    ]
    protected ?string $username = '';

    /**
     * 获取 username - 用户名.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * 赋值 username - 用户名.
     *
     * @param string|null $username username
     *
     * @return static
     */
    public function setUsername(mixed $username): self
    {
        if (\is_string($username) && mb_strlen($username) > 32)
        {
            throw new \InvalidArgumentException('The maximum length of $username is 32');
        }
        $this->username = null === $username ? null : $username;

        return $this;
    }

    /**
     * 密码.
     * password.
     */
    #[
        \Imi\Model\Annotation\Column(name: 'password', type: 'varchar', length: 255, nullable: false)
    ]
    protected ?string $password = null;

    /**
     * 获取 password - 密码.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * 赋值 password - 密码.
     *
     * @param string|null $password password
     *
     * @return static
     */
    public function setPassword(mixed $password): self
    {
        if (\is_string($password) && mb_strlen($password) > 255)
        {
            throw new \InvalidArgumentException('The maximum length of $password is 255');
        }
        $this->password = null === $password ? null : $password;

        return $this;
    }
}
