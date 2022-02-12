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
use BlankPhp\Route\Exception\ParameterException;

class Route
{
    /**
     * @var RouteCollection
     */
    private $routeRules;

    private $attributes = [];

    /**
     * @var string[]
     */
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public function __construct(RouteCollection $collection)
    {
        $this->routeRules = $collection;
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
        if (count($parameter) < 1) {
            throw new ParameterException();
        }
        $set = array_shift($parameter);

        return new RuleGroup($set, $parameter, $this);
    }

    private function add($method, $url, $parameter = []): RouteRule
    {
        return $this->routeRules->add(new RouteRule($method, $url, $parameter, $this->getAttribute()));
    }

    public function setAttributes($attribute)
    {
        $this->attributes[] = $attribute;
    }

    public function getAttribute()
    {
        foreach ($this->attributes as $item) {
            return $item;
        }

        return [];
    }

    public function popAttributes()
    {
        array_pop($this->attributes);
    }

    public function __call(string $name, array $arguments)
    {
        $method = strtoupper($name);
        if (in_array($method, self::$verbs)) {
            return $this->add($method, ...$arguments);
        }
    }
}
