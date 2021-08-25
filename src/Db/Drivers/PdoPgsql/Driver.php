<?php

declare(strict_types=1);

namespace Imi\Pgsql\Db\Drivers\PdoPgsql;

use Imi\App;
use Imi\Bean\Annotation\Bean;
use Imi\Config;
use Imi\Db\Exception\DbException;
use Imi\Db\Statement\StatementManager;
use Imi\Db\Transaction\Transaction;
use Imi\Pgsql\Db\Contract\IPgsqlDb;
use Imi\Pgsql\Db\Contract\IPgsqlStatement;
use Imi\Pgsql\Db\PgsqlBase;
use Imi\Pgsql\Db\Util\SqlUtil;
use PDO;

/**
 * PDO Pgsql驱动.
 *
 * @Bean("PdoPgsqlDriver")
 */
class Driver extends PgsqlBase implements IPgsqlDb
{
    /**
     * 连接对象
     */
    protected ?PDO $instance = null;

    /**
     * 最后执行过的SQL语句.
     */
    protected string $lastSql = '';

    /**
     * Statement.
     *
     * @var \PDOStatement|bool|null
     */
    protected $lastStmt = null;

    /**
     * 是否缓存 Statement.
     */
    protected bool $isCacheStatement = false;

    /**
     * 事务管理.
     */
    protected Transaction $transaction;

    /**
     * 参数格式：
     * [
     * 'host'       => 'Pgsql IP地址',
     * 'username'   => '数据用户',
     * 'password'   => '数据库密码',
     * 'database'   => '数据库名',
     * 'port'       => 'Pgsql 端口，默认5432，可选参数',
     * 'options'    => [], // PDO连接选项
     * ].
     */
    public function __construct(array $option = [])
    {
        $option['username'] ??= 'postgres';
        $option['password'] ??= '';
        $option['options'] ??= [];
        $options = &$option['options'];
        $options[\PDO::ATTR_ERRMODE] ??= \PDO::ERRMODE_EXCEPTION;
        parent::__construct($option);
        $this->isCacheStatement = Config::get('@app.db.statement.cache', true);
        $this->transaction = new Transaction();
    }

    /**
     * 构建DNS字符串.
     */
    protected function buildDSN(): string
    {
        $option = $this->option;
        if (isset($option['dsn']))
        {
            return $option['dsn'];
        }

        return 'pgsql:'
                 . 'host=' . ($option['host'] ?? '127.0.0.1')
                 . ';port=' . ($option['port'] ?? '5432')
                 . ';dbname=' . ($option['database'] ?? 'database')
                 ;
    }

    /**
     * 是否已连接.
     */
    public function isConnected(): bool
    {
        try
        {
            $instance = $this->instance;

            return $instance && false !== $instance->query('select 1');
        }
        catch (\Throwable $e)
        {
        }

        return false;
    }

    /**
     * 打开
     */
    public function open(): bool
    {
        $option = $this->option;
        $this->instance = new \PDO($this->buildDSN(), $option['username'], $option['password'], $option['options']);
        $this->execInitSqls();

        return true;
    }

    /**
     * 关闭.
     */
    public function close(): void
    {
        StatementManager::clear($this);
        if (null !== $this->lastStmt)
        {
            $this->lastStmt = null;
        }
        $this->instance = null;
    }

    /**
     * 获取原对象实例.
     */
    public function getInstance(): PDO
    {
        return $this->instance;
    }

    /**
     * 启动一个事务
     */
    public function beginTransaction(): bool
    {
        if (!$this->inTransaction() && !$this->instance->beginTransaction())
        {
            return false;
        }
        $this->exec('SAVEPOINT P' . $this->getTransactionLevels());
        $this->transaction->beginTransaction();

        return true;
    }

    /**
     * 提交一个事务
     */
    public function commit(): bool
    {
        return $this->instance->commit() && $this->transaction->commit();
    }

    /**
     * 回滚事务
     * 支持设置回滚事务层数，如果不设置则为全部回滚.
     */
    public function rollBack(?int $levels = null): bool
    {
        if (null === $levels)
        {
            $result = $this->instance->rollback();
        }
        else
        {
            $this->exec('ROLLBACK TO P' . ($this->getTransactionLevels()));
            $result = true;
        }
        if ($result)
        {
            $this->transaction->rollBack($levels);
        }

        return $result;
    }

    /**
     * 获取事务层数.
     */
    public function getTransactionLevels(): int
    {
        return $this->transaction->getTransactionLevels();
    }

    /**
     * 检查是否在一个事务内.
     */
    public function inTransaction(): bool
    {
        return $this->instance->inTransaction();
    }

    /**
     * 返回错误码
     *
     * @return mixed
     */
    public function errorCode()
    {
        if ($this->lastStmt)
        {
            return $this->lastStmt->errorCode();
        }
        else
        {
            return $this->instance->errorCode();
        }
    }

    /**
     * 返回错误信息.
     */
    public function errorInfo(): string
    {
        if ($this->lastStmt)
        {
            $errorInfo = $this->lastStmt->errorInfo();
        }
        else
        {
            $errorInfo = $this->instance->errorInfo();
        }
        if (null === $errorInfo[1] && null === $errorInfo[2])
        {
            return '';
        }

        return $errorInfo[1] . ':' . $errorInfo[2];
    }

    /**
     * 获取最后一条执行的SQL语句.
     */
    public function lastSql(): string
    {
        return $this->lastSql;
    }

    /**
     * 执行一条 SQL 语句，并返回受影响的行数.
     */
    public function exec(string $sql): int
    {
        $this->lastSql = $sql;

        $result = $this->instance->exec($sql);
        if (false === $result)
        {
            throw new DbException('SQL prepare error [' . $this->errorCode() . '] ' . $this->errorInfo() . \PHP_EOL . 'sql: ' . $sql . \PHP_EOL);
        }

        return $result;
    }

    /**
     * 批量执行 SQL，返回查询结果.
     */
    public function batchExec(string $sql): array
    {
        $result = [];
        foreach (SqlUtil::parseMultiSql($sql) as $itemSql)
        {
            $queryResult = $this->query($itemSql);
            $fetchResult = $queryResult->fetchAll();
            $result[] = [[]] === $fetchResult ? [] : $fetchResult;
        }

        return $result;
    }

    /**
     * 取回一个数据库连接的属性.
     *
     * @param mixed $attribute
     *
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        return $this->instance->getAttribute($attribute);
    }

    /**
     * 设置属性.
     *
     * @param mixed $attribute
     * @param mixed $value
     */
    public function setAttribute($attribute, $value): bool
    {
        return $this->instance->setAttribute($attribute, $value);
    }

    /**
     * 返回最后插入行的ID或序列值
     */
    public function lastInsertId(?string $name = null): string
    {
        return $this->instance->lastInsertId($name);
    }

    /**
     * 返回受上一个 SQL 语句影响的行数.
     */
    public function rowCount(): int
    {
        return null === $this->lastStmt ? 0 : $this->lastStmt->rowCount();
    }

    /**
     * 准备执行语句并返回一个语句对象
     */
    public function prepare(string $sql, array $driverOptions = []): IPgsqlStatement
    {
        if ($this->isCacheStatement && $stmtCache = StatementManager::get($this, $sql))
        {
            $stmt = $stmtCache['statement'];
        }
        else
        {
            $this->lastSql = $sql;
            $lastStmt = $this->lastStmt = $this->instance->prepare($sql, $driverOptions);
            // @phpstan-ignore-next-line
            if (false === $lastStmt)
            {
                throw new DbException('SQL prepare error [' . $this->errorCode() . '] ' . $this->errorInfo() . \PHP_EOL . 'sql: ' . $sql . \PHP_EOL);
            }
            $stmt = App::getBean(Statement::class, $this, $lastStmt);
            if ($this->isCacheStatement && !isset($stmtCache))
            {
                StatementManager::setNX($stmt, true);
            }
        }

        return $stmt;
    }

    /**
     * 执行一条SQL语句，返回一个结果集作为PDOStatement对象
     */
    public function query(string $sql): IPgsqlStatement
    {
        $this->lastSql = $sql;
        $this->lastStmt = $lastStmt = $this->instance->query($sql);
        if (false === $lastStmt)
        {
            throw new DbException('SQL query error: [' . $this->errorCode() . '] ' . $this->errorInfo() . \PHP_EOL . 'sql: ' . $sql . \PHP_EOL);
        }

        return App::getBean(Statement::class, $this, $lastStmt);
    }

    /**
     * Get 事务管理.
     */
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}
