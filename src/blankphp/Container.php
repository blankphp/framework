<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp;

use BlankPhp\Container\InteractiveBind;
use BlankPhp\Contract\Container as ContainerContract;
use BlankPhp\Contract\Event;
use BlankPhp\Exception\ParameterLoopException;
use ReflectionParameter;

class Container implements \ArrayAccess, ContainerContract, Event
{
    /**
     * @var array
     *            共享实例
     */
    protected $instances = [];

    /**
     * @var array
     *            绑定
     */
    protected $binds = [];

    /**
     * @var array
     *            单例放classes
     */
    protected $classes = [];

    /**
     * 事件.
     *
     * @var array
     */
    protected $events = [];

    /**
     * 别名.
     *
     * @var InteractiveBind
     */
    protected $alice = null;

    /**
     * @var array
     *            处理
     */
    protected $resolve = [];

    /**
     * 三级缓存.
     *
     * @var array
     */
    protected $parameterStatus = [];

    protected function __construct()
    {
        $this->alice = new InteractiveBind();
    }

    protected function getShareObj($abstract)
    {
        return $this->classes[$abstract] ?? null;
    }

    /**
     * @throws \ReflectionException
     * @throws ParameterLoopException
     */
    public function make($abstract, $parameters = [])
    {
        if ($res = $this->getInstances($abstract)) {
            return $res;
        }
        if ($class = $this->alice->getValue($abstract)) {
            $abstract = $class;
        }
        // 是否为绑定
        if ($class = $this->getBinds($abstract)) {
            $abstract = $class;
        }

        return (empty($parameters)) ? $this->instance($abstract, $this->build($abstract)) : $this->instance($abstract, new $abstract(...$parameters));
    }

    private function getInstances($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        $keys = $this->alice->getByKey($abstract);
        foreach ($keys as $item) {
            if (isset($this->instances[$item])) {
                $this->instances[$abstract] = $this->instances[$item];

                return $this->instances[$item];
            }
        }

        return null;
    }

    private function getBinds($binds)
    {
        return $this->binds[$binds] ?? null;
    }

    public function has($abstract)
    {
        return isset($this->instances[$abstract]) || $this->alice->verifyValue($abstract) || isset($this->binds[$abstract]);
    }

    /**
     * @param $instance
     * @param $abstract
     *
     * @return mixed|void
     */
    public function bind($abstract, $instance)
    {
        //清理老数据
        if (null === $instance) {
            $concert = $abstract;
        } else {
            if (is_array($instance)) {
                $concert = $instance[count($instance) - 1];
            } else {
                $concert = $instance;
            }
        }
        $this->binds[$abstract] = $concert;
        $this->bindAlice($abstract, $instance);
    }

    /**
     * @param $abstract
     * @param $instance
     *
     * @return void
     */
    public function bindAlice($abstract, $instance)
    {
        $this->alice->binds($abstract, $instance);
    }

    /**
     * @param $abstract
     * @param $instance
     *
     * @return mixed|void
     *                    实例注册
     */
    public function instance($abstract, $instance)
    {
        foreach ($this->alice->getByKey($abstract) as $item) {
            $this->instances[$item] = $instance;
        }
        $this->instances[$abstract] = $instance;
        unset($this->binds[$abstract]);

        return $instance;
    }

    /**
     * @param $concrete
     *
     * @return mixed
     */
    public function notInstantiable($concrete)
    {
        throw new \RuntimeException("[$concrete] no Instantiable", 3);
    }

    /**
     * @param $concrete
     *
     * @return mixed|object|void|null
     *
     * @throws \ReflectionException
     * @throws ParameterLoopException
     */
    public function build($concrete, int $count = 0)
    {
        // 清空
        if (!$count) {
            $this->clearStatus();
        }

        try {
            $reflector = new \ReflectionClass($concrete);
            $this->setWantGet($concrete);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException("Reflection [$concrete] error");
        }

        if (!$reflector->isInstantiable()) {
            $this->notInstantiable($concrete);
        }

        $constructor = $reflector->getConstructor();

        if (null === $constructor) {
            // 判断cache中是否拥有
            return $this->newObj($concrete);
        }

        if ($reflector->isInstantiable()) {
            // 获得目标函数
            $params = $constructor->getParameters();
            if (0 === count($params)) {
                return $this->newObj($concrete);
            }
            $paramsArray = $this->resolveDepends($constructor->getParameters(), ++$count);

            return $reflector->newInstanceArgs($paramsArray);
        }
    }

    /***
     * @param array $params
     * @param $count
     * @return array
     * @throws ParameterLoopException
     * @throws \ReflectionException
     */
    public function resolveDepends(array $params, $count): array
    {
        // 判断参数类型
        ++$count;
        /**
         * @var ReflectionParameter $param
         */
        foreach ($params as $param) {
            $args = null;
            if ($paramType = $param->getType()) {
                // 获得参数类型名称
                if ($paramType->isBuiltin()) {
                    $args = $this->getDefault($paramType);
                } else {
                    $paramClassName = $paramType->getName();
                    // 缓存
                    if (1 === $this->getStatus($paramClassName)) {
                        throw new ParameterLoopException('Find the construct loop', $this->parameterStatus);
                    }
                    if ($this->has($paramClassName)) {
                        $args = $this->make($paramClassName);
                    } else {
                        $args = $this->build($paramClassName, $count);
                    }
                }
            }
            $paramArr[] = $args;
        }

        return $paramArr ?? [];
    }

    /**
     * @param $paramType
     *
     * @return array|false|int|null
     */
    private function getDefault($paramType)
    {
        if ($paramType->allowsNull()) {
            return null;
        }
        switch ($paramType->getName()) {
            case 'int':
                return 0;
            case 'bool':
            case 'boolean':
                return false;
            case 'array':
                return [];
            default:
                return null;
        }
    }

    /**
     * @return void
     */
    private function clearStatus()
    {
        $this->parameterStatus = [];
    }

    /**
     * @param $concrete
     *
     * @return mixed
     */
    private function newObj($concrete)
    {
        return new $concrete();
    }

    /**
     * @param $concrete
     *
     * @return int|mixed
     */
    private function getStatus($concrete)
    {
        return $this->parameterStatus[$concrete] ?? 0;
    }

    /**
     * @param $key
     */
    private function setWantGet($key): int
    {
        return $this->parameterStatus[$key] = 1;
    }

    /**
     * @param $key
     * @param $v
     *
     * @return mixed
     */
    private function putStatus($key, $v)
    {
        return $this->parameterStatus[$key] = $v;
    }

    /**
     * @param $instance
     * @param $method
     *
     * @return object|void
     *
     * @throws \ReflectionException
     */
    public function call($instance, $method, array $param = [])
    {
        $instance = $this->make($instance);

        return $instance->{$method}(...$param);
    }

    public function flush(): void
    {
        $this->classes = [];
        $this->alice = [];
        $this->binds = [];
        $this->events = [];
        $this->parameterStatus = [];
    }

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        if ($this->has($offset)) {
            return true;
        }

        return false;
    }

    /**
     * @param $offset
     *
     * @return mixed|void
     *
     * @throws ParameterLoopException
     * @throws \ReflectionException
     */
    public function offsetGet($offset)
    {
        return $this->make($offset);
    }

    /**
     * @param $offset
     * @param $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->classes[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->binds[$offset], $this->instances[$offset]);
    }

    public function alice($name, $class): bool
    {
        return class_alias($class, $name);
    }
}
