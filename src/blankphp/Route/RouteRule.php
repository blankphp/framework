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

    private $namespace = '';

    private $method;

    private $controller;

    private $parameter;

    private $domain;

    private $as;

    private $where;

    private $prefix;

    public function __construct($method, $url, $parameter, $attributes = [])
    {
        $this->method = $method;
        $this->url = $url;
        $this->setAttribute($attributes);
        $this->parse($parameter);
    }

    private function setAttribute($attributes)
    {
        foreach ($attributes as $k => $v) {
            if (isset($this->{$k})) {
                $this->$k = $v;
            }
        }
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
        } else {
            // 控制器
            $this->controller($parameter);
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

    public function uses($controller)
    {
        $this->controller($controller);
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

    public function controller($controller)
    {
        if ($controller instanceof \Closure) {
            $this->controller = $controller;
        } else {
            $this->controller = $this->makeController($controller);
        }
    }

    private function makeController($controllerArr)
    {
        if (is_string($controllerArr)) {
            $controllerArr = explode('@', $controllerArr);
        }
        if (!empty($this->namespace)) {
            $controllerArr[0] = ltrim($this->namespace, '\\').'\\'.$controllerArr[0];
        }

        return $controllerArr;
    }

    private function parent()
    {
    }

    public function getUrlParameter(): array
    {
        return [];
    }

    public function getOther()
    {
        return [
            'method' => $this->method,
            'controller' => $this->controller,
            'middleware' => $this->middleware,
        ];
    }

    public function getUrl()
    {
        return trim($this->prefix, '/').'/'.trim($this->url, '/');
    }

    public function getController()
    {
        return $this->controller;
    }
}
