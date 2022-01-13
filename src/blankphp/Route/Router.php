<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/14
 * Time: 16:06
 */

namespace BlankPhp\Route;


use BlankPhp\Application;
use BlankPhp\Contract\Request;
use BlankPhp\Exception\HttpException;
use BlankPhp\Provider\MiddleWareProvider;
use BlankPhp\Response\Response;
use BlankPhp\Route\Traits\ResolveSomeDepends;
use JetBrains\PhpStorm\Pure;

/**
 * 寻找路由
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
    public function findRoute(Request $request){
        return $this->route->match($request->getUri(),$request->getMethod());
    }

    public function prepareResponse($res): Response
    {
        return new Response($res);
    }

    /**
     * @throws \ReflectionException
     */
    public function runController($controller){
        $urlVars = $this->getUrlVars();
        if (is_array($controller)){
            $target = new $controller[0]();
            return $target->{$controller[1]}($this->resolveClassMethodDependencies($urlVars,$controller[0],$controller[1]));
        }
        if ($controller instanceof \Closure){
            return $controller($this->resolveClassMethodDependencies($urlVars,'Closure',$controller));
        }
        // 其他

    }

    private function getUrlVars(): array
    {
        return [];
    }

    private function translate($middleware){
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
        $controller = $routeRule->getController();
        return (new Pipe)
            ->send($request)
            ->through($this->translate($routeRule->middleware))
            ->run(function () use ($controller) {
                return $this->prepareResponse($this->runController($controller));
            });
    }



}