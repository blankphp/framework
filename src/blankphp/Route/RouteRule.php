<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Route;

/**
 * Class RouteRule.
 */
class RouteRule
{
    private $url;

    public $middleware = [];

    private $name;

    private $method;

    private $controller;

    private $parameter;

    private $domain;

    private $as;

    private $uses;

    private $where;

    public function __construct($method, $url, $parameter)
    {
        $this->method = $method;
        $this->url = $url;
        $this->parse($parameter);
    }

    private function parse($parameter)
    {
        if (is_array($parameter)) {
            foreach ($parameter as $method => $value) {
                if (is_string($method)) {
                    if (is_array($value)) {
                        $this->{$method}(...$value);
                    } else {
                        $this->{$method}($value);
                    }
                    continue;
                }
                throw new \RuntimeException('parameter error');
            }
        }
        if ($parameter instanceof \Closure) {
            $this->controller = $parameter;
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function middleware(...$args)
    {
        $this->middleware = array_merge($this->middleware, $args);
    }

    public function uses(...$controller)
    {
        $this->uses = $controller;
    }

    public function where($name, $preg)
    {
        $this->where[$name] = $preg;
    }

    public function name($name)
    {
        $this->name = $name;
    }

    public function as($name)
    {
        $this->as = $name;
    }

    public function domain($domain)
    {
    }

    private function parent()
    {
    }

    public function getOther()
    {
        // 获取所有 attribute 放入数组

        return [
            'method' => $this->method,
            'controller' => $this->controller,
            'middleware' => $this->middleware,
        ];
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getController()
    {
        return $this->controller;
    }
}
