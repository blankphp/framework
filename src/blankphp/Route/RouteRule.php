<?php


namespace Blankphp\Route;

/**
 * Class RouteRule
 * @package Blankphp\Route
 * 路由规则
 */
class RouteRule implements \ArrayAccess
{
    //匹配规则
    public $rule = "";
    //对应方法
    public $method = "";
    //对应方法
    public $action = "";
    //路由对应的变量
    public $name = null;
    //中间件
    public $middleware = [];
    //所属组
    public $group = "";
    //中间件组
    public $middlewareGroup = [];
    //路由变量
    public $vars = [];
    //路由参数
    public $option = [];
    //模式字符串
    private $pattern = "#<(.+?)>#";

    public function set($method, $rule, $action, $name = '', $group = "", $middlewareGroup = [])
    {
        $this->setMethod($method);
        //分析rule字符串中的模式
        $this->setRule($rule);
        $this->setAction($action);
        $this->name($name);
        $this->setGroup($group);
        $this->setMiddlewareGroup($middlewareGroup);
    }

    public function fromArray(array $route)
    {
        $this->setRule($route['rule']);
        $this->setName($route['name']);
        $this->setAction($route["action"]);
        $this->middleware($route['middleware']);
        $this->setGroup($route['group']);
        $this->setMethod($route['method']);
    }

    //转换为数组
    public function toArray()
    {
        return [
            "rule" => $this->getRule(),
            "name" => $this->getName(),
            "action" => $this->getAction(),
            "middleware" => $this->getMiddleware(),
            "group" => $this->getGroup(),
            "method" => $this->getMethod(),
        ];
    }

    /**
     * @param array $middleware
     */
    private function setMiddleware($middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * @param string $group
     */
    private function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @param array $vars
     */
    public function setVars($vars)
    {
        $this->vars = $vars;
    }


    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param null $name
     */
    public function name($name)
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
    public function middleware($middleware)
    {
        if (is_array($middleware)) {
            $this->middleware = array_merge($this->middleware, $middleware);
        }
        $this->middleware = $middleware;
    }


    public function getGroup()
    {
        return $this->group;
    }


    public function getMiddlewareGroup()
    {
        return $this->middlewareGroup;
    }


    public function setMiddlewareGroup($middlewareGroup)
    {
        $this->middlewareGroup = array_merge($this->middlewareGroup, $middlewareGroup);;
    }


    /**
     * @return mixed
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @param mixed $rule
     */
    private function setRule($rule)
    {
        //替换目标字符串
        $rule = preg_replace($this->pattern, "(\\1)", $rule);
        $this->rule = $rule;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    private function setMethod($method)
    {
        $this->method = $method;
    }


    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }


    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return !empty($this->{$offset});
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        $this->{$offset} = null;
    }
}