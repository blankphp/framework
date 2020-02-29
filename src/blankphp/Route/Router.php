<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/14
 * Time: 16:06
 */

namespace Blankphp\Route;


use Blankphp\Application;
use Blankphp\Provider\MiddleWareProvider;
use Blankphp\Response\Response;

class Router
{
    //对路由分发进行一个封装
    protected $route;
    protected $app;
    protected $middleware;

    public function __construct(Route $route)
    {
        $this->route = $route;
        $this->app = Application::getInstance();
    }

    public function getMiddleware($group = "web")
    {
        $middleware = $this->app->getSignal('GroupMiddleware', $this->route->getGroupMidlleware());
        $temp = $this->app->getSignal('AliceMiddleware', $this->route->getMiddleWare());
        $this->middleware = array_filter(array_merge($middleware[$group], $temp));
    }

    public function dispatcher($request)
    {
        ///寻找出request
        $controller = $this->route->findRoute($request);
        $this->getMiddleware($controller->group);
        return (new Pipe)
            ->send($request)
            ->through($this->middleware)
            ->run(function () use ($controller) {
                return $this->prepareResponse($this->route->runController($controller->action[0],$controller->action[1],$controller->getVars()));
            });
    }

    public function prepareResponse($response)
    {
        return self::toResponse($response);
    }

    public static function toResponse($response)
    {
        $response = new Response($response);
        return $response->prepare();
    }

    public function flush()
    {
        $this->middleware = [];
    }


}