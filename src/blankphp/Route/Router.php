<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Route;

use BlankPhp\Contract\Request;
use BlankPhp\Exception\HttpException;
use BlankPhp\Response\Response;
use BlankPhp\Route\Traits\ResolveSomeDepends;

/**
 * 寻找路由.
 */
class Router
{
    use ResolveSomeDepends;

    /** @var Route */
    protected $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @throws HttpException
     */
    public function findRoute(Request $request)
    {
        return $this->route->match($request->getUri(), $request->getMethod());
    }

    public function prepareResponse($res): Response
    {
        return new Response($res);
    }

    /**
     * @return mixed|void
     *
     * @throws \ReflectionException
     */
    public function runController(RouteRule $route)
    {
        $controller = $route->getController();
        if (is_array($controller)) {
            $target = new $controller[0]();
            // 路由参数通过路由解析获取
            return $target->{$controller[1]}(...$this->resolveClassMethodDependencies($route->getUrlParameter(), $controller[0], $controller[1]));
        }
        if ($controller instanceof \Closure) {
            return $controller($this->resolveClassMethodDependencies($route->getUrlParameter(), 'Closure', $controller));
        }
    }

    private function getUrlVars(): array
    {
        return [];
    }

    private function translate($middleware)
    {
        // 解析middleware
        return [];
    }

    /**
     * @throws HttpException
     */
    public function dispatcher($request)
    {
        ///寻找出request
        /** @var RouteRule $routeRule */
        $routeRule = $this->findRoute($request);

        return (new Pipe())
            ->send($request)
            ->through($this->translate($routeRule->middleware))
            ->run(function () use ($routeRule) {
                return $this->prepareResponse($this->runController($routeRule));
            });
    }
}
