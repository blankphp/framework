<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp;

abstract class Facade
{
    protected static $resolveFacadeInstances = [];

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
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
        return self::getFromApp();
    }

    public static function getFromApp()
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

    public static function __callStatic($method, $args)
    {
        $obj = static::resolveFacadeInstance();
        //通过反射解决依赖
        return $obj->$method(...$args);
    }
}
