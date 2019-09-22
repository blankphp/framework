<?php


namespace Blankphp\Route;

/**
 * Class RouteRule
 * @package Blankphp\Route
 * 路由规则
 */
class RouteRule
{
    //匹配规则
    public $rule;
    //对应方法
    public $method;

    public $action;
    //路由对应的变量
//    public $item = [];
    public $name = null;
    //中间件
    public $middleware = [];
    //所属组
    public $group = [];
    //中间件组
    public $middlewareGroup = [];

    public function set($method, $rule, $action, $name = '', $group = [], $middlewareGroup = [])
    {
        $this->method = $method;
        $this->rule = $rule;
        $this->action = $action;
        $this->name = $name;
        $this->group = $group;
        $this->middlewareGroup = $middlewareGroup;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * @param array $middleware
     */
    public function setMiddleware($middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * @return array
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param array $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return array
     */
    public function getMiddlewareGroup()
    {
        return $this->middlewareGroup;
    }

    /**
     * @param array $middlewareGroup
     */
    public function setMiddlewareGroup($middlewareGroup)
    {
        $this->middlewareGroup = $middlewareGroup;
    }



}