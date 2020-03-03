<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 14:52
 */

namespace Blankphp\Database;


use Blankphp\Application;
use Blankphp\Database\Query\Builder;
use Blankphp\Database\Query\Raw;
use Blankphp\Database\Traits\DBFunction;
use Blankphp\Database\Traits\DBJoin;
use Blankphp\Exception\DataBaseTypeException;
use Blankphp\Facade\Log;
use Helpers\Str;

class Database
{
    use DBFunction, DBJoin;
    private static $pdo = null;
    protected $sql;
    protected $id = 'default';
    protected $collection;
    protected $PDOsmt;
    protected $driver;
    protected $config;


    public function __construct(Builder $sql, $config = [])
    {
        $this->sql = $sql;
        self::$pdo = $this->connectFactory();
        $driver = config('db.default');
        $this->sql->engine($driver);
        if (!empty($config)) {
            $this->config = $config;
        } else {
            $this->config = config(Str::merge('db.database', $driver, '.'));
        }
    }

    public function connectFactory()
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
     * 执行查询语句
     */
    public function query($sql = '')
    {
        //执行sql
        //返回集合
    }

    /**
     * @param string $sql
     * 返回行数目
     */
    public function execute($sql = '')
    {

    }

    /**
     * 选定表
     * @param $table
     * @return $this
     */
    public function table($table)
    {
        $this->sql->from($table);
        return $this;
    }

    public function update(array $values = [])
    {
        $this->sql->updateSome($values);
        return $this->commit()->rowCount();
    }

    protected function beginTransaction()
    {
        $this->connect();
        self::$pdo->beginTransaction();
    }

    protected function commitTransaction()
    {
        $this->connect();
        self::$pdo->commit();
    }

    protected function rollBack()
    {
        $this->connect();
        self::$pdo->rollBack();
    }

    final function transaction(\Closure $closure)
    {
        try {
            $this->beginTransaction();
            $closure();
            $this->commitTransaction();
        } catch (\PDOException $exception) {
            $this->rollBack();
        }
    }

    public function raw($string)
    {
        $raw = new Raw($string);
        return $raw;
    }

    public function connect()
    {
        if (self::$pdo instanceof \Generator) {
            self::$pdo->next();
            self::$pdo = self::$pdo->current();
        }
    }

    public function all()
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
        $smt = self::$pdo->prepare($this->sql->toSql());
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

    public function get()
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
                $this->sql->deleteSome($id = null, $arg);
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
        $data = $result->fetchObject(Collection::class);
        return $data;
    }

    public function limit()
    {
        $args = func_get_args();
        $count = count($args);
        if ($count > 2)
            throw new \Exception('错误的范围');
        elseif ($count == 1)
            $this->sql->limit([0, $args[0]]);
        elseif ($count == 2)
            $this->sql->limit($args);
        return $this;

    }

    public function __set($name, $value)
    {
        //修改或者创建某个表中的元素..得判断有没有获取到目标id

    }


    public function __call($name, $arguments)
    {
        $this->sql->{$name}(...$arguments);
        return $this;
    }

    //将数据进行绑定,,Connect?
    public function bindValues(array $values = [])
    {
        if (is_null($this->PDOsmt)) {
            throw new DataBaseTypeException('异常错误');
        }
        $i = 0;
        foreach ($values as $key => $value) {
            if (!empty($value)) {
                foreach ($value as $k => $item) {
                    $b = is_numeric($k) ? ++$i : $k;
                    if (is_int($item)) {
                        $this->PDOsmt->bindValue($b, $item, \PDO::PARAM_INT);
                    } elseif (is_null($item)) {
                        $this->PDOsmt->bindValue($b, $item, \PDO::PARAM_NULL);
                    } else {
                        $this->PDOsmt->bindValue($b, (string)$item, \PDO::PARAM_STR);
                    }
                }
            }
        }
    }

    public function bindCall(array $values)
    {
        if (is_null($this->PDOsmt)) {
            throw new Exception('异常错误');
        }
        foreach ($values as $key => $value) {
            if (!empty($value)) {
                foreach ($value as $k => $item) {
                    $b = is_numeric($k) ? $k + 1 : ':' . $k;
                    $this->PDOsmt->bindValue($b, $item);
                }
            }
        }
    }

}