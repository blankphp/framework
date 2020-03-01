<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 14:19
 */

namespace Blankphp;

use Blankphp\Cache\Driver\File;
use \Blankphp\Contract\Container as ContainerContract;
use Blankphp\Exception\NotFoundClassException;


class Container implements \ArrayAccess, ContainerContract
{
    /**
     * @var
     * 存储单例
     */
    protected static $instance;
    /**
     * @var array
     * 共享实例
     */
    protected $instances = [];
    /**
     * @var array
     * 绑定
     */
    protected $binds = [];
    /**
     * @var array
     * 单例放classes
     */
    protected $classes = [];

    /**
     * 公用信息
     * @var array
     */
    public $signal = [];

    /**
     * 事件
     * @var array
     */
    protected $events = [];

    /**
     * 别名
     * @var array
     */
    protected $alice = [];


    /**
     * @var array
     * 处理
     */
    protected $resolve = [];


    /**
     * 单例模式
     * @return mixed
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            new static();
        }
        return static::$instance;
    }

    protected function getShareObj($abstract)
    {
        return isset($this->classes[$abstract]) ? $this->classes[$abstract] : null;
    }


    public function make($abstract, $parameters = [])
    {
        if ($res = $this->getShareObj($abstract)) {
            return $res;
        }

        if (!empty($res = $this->getAlice($abstract))) {
            $abstract = $res;
        }

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $class = $this->binds[$abstract]["concert"];
        return (empty($parameters)) ? $this->instance($abstract, $this->build($class)) : $this->instance($abstract, new $class(...$parameters));
    }


    public function has($abstract)
    {
        return (isset($this->instances[$abstract]) || isset($this->alice[$abstract]) || isset($this->binds[$abstract]) || isset($this->classes[$abstract]));
    }

    /**
     * @param $abstract
     * @param $instance
     * @param $share
     * @return mixed|void
     * 绑定别名
     */
    public function bind($instance, $abstract, $share = false)
    {
        //清理老数据
        if (is_null($instance)) {
            $concert = $abstract;
        } else {
            if (is_array($instance)) {
                $concert = $instance[count($instance) - 1];
            } else {
                $concert = $instance;
            }
        }
        $this->binds[$abstract] = compact("concert", "share");
        $this->bindAlice(is_array($instance) ? $instance : [$instance], $abstract);
    }


    public function bindAlice(array $class, $abstract)
    {
        foreach ($class as $item) {
            $this->alice[$item] = $abstract;
        }
    }

    public function getAlice($abstract)
    {
        return isset($this->alice[$abstract]) ? $this->alice[$abstract] : null;
    }


    /**
     * @param $abstract
     * @param $instance
     * @return mixed|void
     * 绑定signal
     */
    public function signal($abstract, $instance)
    {
        $this->signal[$abstract] = $instance;
    }

    /**
     * @param $abstract
     * @param $instance
     * @param $share
     * @return mixed|void
     * 直接创建实例
     */
    public function instance($abstract, $instance, $share = false)
    {
        if ($share)
            $this->classes[$abstract] = $instance;

        $this->instances[$abstract] = $instance;

        unset($this->binds[$abstract]);

        return $instance;
    }


    public function notInstantiable($concrete)
    {
        throw new \Exception("[$concrete] no Instanctiable", 3);
    }

    public function build($concrete)
    {
        $reflector = new \ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            return $this->notInstantiable($concrete);
        }
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }
        if ($reflector->isInstantiable()) {
            // 获得目标函数
            $params = $constructor->getParameters();
            if (count($params) === 0) {
                return new $concrete();
            }
            $paramsArray = $this->resolveDepends($constructor->getParameters());
            return $reflector->newInstanceArgs($paramsArray);
        }
    }

    /**
     * @param array $params
     * @return array
     * 解决依赖注入问题
     */
    public function resolveDepends($params = [])
    {
        // 判断参数类型
        foreach ($params as $key => $param) {
            if ($paramClass = $param->getClass()) {
                // 获得参数类型名称
                $paramClassName = $paramClass->getName();
                // 获得参数类型
                if (isset($this->classes[$paramClassName])) {
                    $args = $this->classes[$paramClassName];
                } else
                    if ($this->has($paramClassName))
                        $args = $this->make($paramClassName);
                    else
                        $args = $this->build($paramClassName);
                $paramArr[] = $args;
            }
        }
        return $paramArr;
    }


    /**
     * @param $instance
     * @param null $method
     * @param array $param
     * @param array $construct
     * @return object|void
     */
    public function call($instance, $method = null, $construct = null, array $param = [])
    {
        $abstract = $instance;
        if (empty($construct)) {
            $instance = new $instance();
        } else {
            $instance = $this->build($instance);
        }
        if (is_null($method)) {
            $this->instance($abstract, $instance, true);
            unset($abstract);
            return $instance;
        }
        return $instance->{$method}(...$param);
    }


    public function flush()
    {
        $this->classes = [];
        $this->alice = [];
        $this->binds = [];
        $this->events = [];
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        if ($this->has($offset))
            return true;
        return false;
    }

    /**
     * @param mixed $offset
     * @return mixed|object|void
     */
    public function offsetGet($offset)
    {
        return $this->make($offset);
    }

    /**
     *  * 为一个元素的赋值
     *  * @param offset
     *  * @param value
     *  */
    public function offsetSet($offset, $value)
    {
        $this->classes[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->binds[$offset], $this->instances[$offset]);
    }

    public function alice($name, $class)
    {
        return class_alias($class, $name);
    }

}



