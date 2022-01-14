<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Route;

use BlankPhp\Exception\HttpException;

class Route
{
    /**
     * @var RouteCollection
     */
    private $routeRules;

    /**
     * @var string[]
     */
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public function __construct()
    {
        $this->routeRules = new RouteCollection();
    }

    /**
     * @throws HttpException
     */
    public function match($uri, $method)
    {
        $routes = $this->routeRules->pregUri($uri);
        // 校验
        if (empty($routes)) {
            throw new HttpException('Not Found', 404);
        }
        /** @var RouteRule $routes */
        if (isset($routes[$method])) {
            return $routes[$method];
        }
        throw new HttpException('Method Not Allow!', 405);
    }

    public function group(...$parameter): RuleGroup
    {
        $set = array_shift($parameter);
        $group = new RuleGroup($set, $parameter);

        return $this->routeRules->addGroup($group);
    }

    private function add($method, $url, $parameter = []): RouteRule
    {
        return $this->routeRules->add(new RouteRule($method, $url, $parameter));
    }

    public function __call(string $name, array $arguments)
    {
        $method = strtoupper($name);
        if (in_array($method, self::$verbs)) {
            return $this->add($method, ...$arguments);
        }
    }
}
