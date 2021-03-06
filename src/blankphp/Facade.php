<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 18:50
 */

namespace BlankPhp;


abstract class Facade
{
    protected static $resolveFacadeInstances = [];

    protected static function getFacadeAccessor()
    {
        throw new \RuntimeException('你没有指定的代理类', 1);
    }

    protected function clearResolveInstance($instance): void
    {
        unset(static::$resolveFacadeInstances[$instance]);
    }

    protected function clearResolveInstances($instance): void
    {
        static::$resolveFacadeInstances = [];
    }

    public static function resolveFacadeInstance()
    {
        $className = static::getFacadeAccessor();
        if (is_object($className)) {
            return $className;
        }
        if (isset(static::$resolveFacadeInstances[$className])) {
            return static::$resolveFacadeInstances[$className];
        }
        $obj = Application::getInstance()->make($className);
        static::$resolveFacadeInstances[static::getFacadeAccessor()] = $obj;
        return $obj;
    }

    public static function __CallStatic($method, $args)
    {
        $obj = static::resolveFacadeInstance();
        //通过反射解决依赖
        return $obj->$method(...$args);
    }

}