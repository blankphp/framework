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


class Container implements \ArrayAccess, ContainerContract
{
    //存储对象的类变量/静态变量
    protected static $instance;
    //共享实例在这里存放
    protected $instances = [];
    //注册实例放入
    protected $binds = [];
    //创建的单例放入class
    protected $classes = [];
    //signal存放一些配置信息等
    public $signal = [];


    /**
     * 单例模式
     * @return mixed
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function make($abstract, $parameters = [])
    {
        //如果这里有实例那么就直接返回注册好的共享实例
        if (isset($this->classes[$abstract])) {
            return $this->classes[$abstract];
        }
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        $class = $this->binds[$abstract];
        return (empty($parameters)) ? $this->instances[$abstract]=$this->build($class) : $this->instances[$abstract]=new $class(...$parameters);
    }

    public function has($abstract)
    {
        return (isset($this->binds[$abstract]) || isset($this->instances[$abstract]) || isset($this->classes[$abstract]));
    }

    public function bind($abstract, $instance)
    {

        if (!is_array($instance))
            $this->binds[$abstract] = $instance;
        else {
            $data = [];
            $desc = '';
            foreach ($instance as $item) {
                if (class_exists($item)) {
                    $desc = $item;
                } elseif (interface_exists($item)) {
                    $data[] = $item;
                }
            }
            $this->binds[$abstract] = $desc;
            foreach ($data as $datum) {
                $this->binds[$datum] = $desc;
            }
        }
    }



    public function signal($abstract, $instance)
    {
        $this->signal[$abstract] = $instance;
    }

    public function instance($abstract, $instance)
    {
        if (!isset($this->instances[$abstract]))
            $this->instances[$abstract] = $instance;
        if (!isset($this->classes[get_class($instance)]))
            $this->classes[get_class($instance)] = $instance;
        unset($this->binds[$abstract]);
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
            if (count($params) === 0)
                return new $concrete();
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
     * @return object|void
     */
    public function call($instance, $method=null,array $param=[])
    {
        $instance = $this->build($instance);
        if (is_null($method))
            return $instance;
        return $instance->$method(...$param);
    }


    public function flush()
    {
        $this->classes = [];
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
        unset($this->binds[$offset]);
    }


}



