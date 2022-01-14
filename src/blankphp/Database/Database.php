<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Database;

use BlankPhp\Connect\Connect;
use BlankPhp\Database\Query\Builder;
use BlankPhp\Database\Query\Raw;
use BlankPhp\Database\Traits\DBFunction;
use BlankPhp\Database\Traits\DBJoin;
use BlankPhp\Exception\DataBaseTypeException;
use BlankQwq\Helpers\Str;

class Database implements Connect
{
    use DBFunction;
    use DBJoin;

    /**
     * @var \PDO
     */
    private $pdo = null;
    /**
     * @var Builder
     */
    protected $sql;
    /**
     * @var string
     */
    protected $id = 'default';
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var \PDOStatement
     */
    protected $PDOsmt;

    /**
     * @var array
     */
    protected $config;

    public function __construct(Builder $sql, $config = [])
    {
        $this->sql = $sql;
        $this->pdo = $this->connectFactory();
        $driver = config('db.default');
        $this->sql->engine($driver);
        if (!empty($config)) {
            $this->config = $config;
        } else {
            $this->config = config(Str::merge('db.database', $driver, '.'));
        }
    }

    private function connectFactory(): ?\Generator
    {
        yield;
        yield DbConnect::getPdo($this->config);
    }

    public function lastInsertId(...$args)
    {
        return $this->pdo->lastInsertId(...$args);
    }

    public function errorInfo()
    {
        return $this->pdo->errorInfo();
    }

    /**
     * @param string $sql
     *                    执行查询语句
     */
    public function query($sql = ''): void
    {
        //执行sql
        //返回集合
    }

    /**
     * @param string $sql
     *                    返回行数目
     */
    public function execute($sql = ''): void
    {
    }

    /**
     * 选定表.
     *
     * @param $table
     *
     * @return $this
     */
    public function table($table): self
    {
        $this->sql->from($table);

        return $this;
    }

    public function update(array $values = [])
    {
        $this->sql->updateSome($values);

        return $this->commit()->rowCount();
    }

    protected function beginTransaction(): void
    {
        $this->connect();
        $this->pdo->beginTransaction();
    }

    protected function commitTransaction(): void
    {
        $this->connect();
        $this->pdo->commit();
    }

    protected function rollBack(): void
    {
        $this->connect();
        $this->pdo->rollBack();
    }

    final public function transaction(\Closure $closure): void
    {
        try {
            $this->beginTransaction();
            $closure();
            $this->commitTransaction();
        } catch (\PDOException $exception) {
            $this->rollBack();
        }
    }

    public function raw($string): Raw
    {
        return new Raw($string);
    }

    public function connect(): void
    {
        if ($this->pdo instanceof \Generator) {
            $this->pdo->next();
            $this->pdo = $this->pdo->current();
        }
    }

    public function all(): void
    {
    }

    public function commit()
    {
        $this->connect();
        $this->PDOsmt = null;

        return $this->_commit();
    }

    public function _commit()
    {
        $smt = $this->pdo->prepare($this->sql->toSql());
        $this->PDOsmt = $smt;
        $procedure = in_array(substr($smt->queryString, 0, 4), ['exec', 'call']);
        if ($procedure) {
            $this->bindCall($this->sql->binds);
        } else {
            $this->bindValues($this->sql->binds);
        }
        $smt->execute();
        $this->sql->flush();

        return $smt;
    }

    public function get(): Collection
    {
        $result = $this->commit();
        //这样只有单一的数据，需要重复的创建然后保存到一个大collection
        $collection = new Collection();
        $collection->setTable($this->sql->table);
        $collection->setTable($this->sql->select);
        while ($data = $result->fetchObject(Collection::class)) {
            $collection->item($data);
        }

        return $collection;
    }

    public function create(array $value)
    {
        $this->sql->insertSome($value);

        return $this->commit()->rowCount();
    }

    public function delete()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $this->sql->deleteSome($arg);
            } elseif (is_numeric($arg)) {
                $this->sql->deleteSome($arg);
            }
        }
        if (empty($args)) {
            $this->sql->deleteSome();
        }

        return $this->commit()->rowCount();
    }

    public function find($id)
    {
        $this->sql->where('id', '=', $id);
        $this->limit(1);

        return $this->commit()->fetchObject(Collection::class);
    }

    public function first()
    {
        $result = $this->commit();
        //这样只有单一的数据，需要重复的创建然后保存到一个大collection
        return $result->fetchObject(Collection::class);
    }

    public function limit(): Database
    {
        $args = func_get_args();
        $count = count($args);
        if ($count > 2) {
            throw new \Exception('错误的范围');
        }
        if (1 === $count) {
            $this->sql->limit([0, $args[0]]);
        } elseif (2 === $count) {
            $this->sql->limit($args);
        }

        return $this;
    }

    public function __call($name, $arguments)
    {
        $this->sql->{$name}(...$arguments);

        return $this;
    }

    //将数据进行绑定,,Connect?
    public function bindValues(array $values = []): void
    {
        if (null === $this->PDOsmt) {
            throw new DataBaseTypeException('异常错误');
        }
        $i = 0;
        foreach ($values as $key => $value) {
            if (!empty($value)) {
                foreach ($value as $k => $item) {
                    $b = is_numeric($k) ? ++$i : $k;
                    if (is_int($item)) {
                        $this->PDOsmt->bindValue($b, $item, \PDO::PARAM_INT);
                    } elseif (null === $item) {
                        $this->PDOsmt->bindValue($b, $item, \PDO::PARAM_NULL);
                    } else {
                        $this->PDOsmt->bindValue($b, (string) $item, \PDO::PARAM_STR);
                    }
                }
            }
        }
    }

    public function bindCall(array $values): void
    {
        if (null === $this->PDOsmt) {
            throw new Exception('异常错误');
        }
        foreach ($values as $key => $value) {
            if (!empty($value)) {
                foreach ($value as $k => $item) {
                    $b = is_numeric($k) ? $k + 1 : ':'.$k;
                    $this->PDOsmt->bindValue($b, $item);
                }
            }
        }
    }

    public function disconnect(): void
    {
    }

    public function reconnect(): void
    {
    }
}
