<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 21:32
 */

namespace BlankPhp\Route;

use BlankPhp\Application;
use BlankPhp\Collection\Collection;
use BlankPhp\Contract\Request;
use BlankPhp\Contract\Route as Contract;
use BlankPhp\Exception\HttpException;
use BlankQwq\Helpers\Arr;
use BlankQwq\Helpers\File;
use BlankQwq\Helpers\Re;
use BlankPhp\Route\Exception\NotFoundRouteException;
use BlankPhp\Route\Exception\RouteErrorException;
use BlankPhp\Route\Traits\ResolveSomeDepends;
use BlankQwq\Helpers\Str;

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
    public function match($uri, $method){
        $routes = $this->routeRules->pregUri($uri);
        // 校验
        if (empty($routes)){
            throw new HttpException('Not Found',404);
        }
        /** @var RouteRule $routes */
        if (isset($routes[$method])){
            return $routes[$method];
        }
        throw new HttpException('Method Not Allow!',405);
    }

    public function group(...$parameter): RuleGroup
    {
        $set = array_shift($parameter);
        $group = new RuleGroup($set,$parameter);
        return $this->routeRules->addGroup($group);
    }

    private function add($method,$url,$parameter=[]): RouteRule
    {
        return $this->routeRules->add(new RouteRule($method,$url,$parameter));
    }

    public function __call(string $name, array $arguments)
    {
        $method = strtoupper($name);
        if (in_array($method,self::$verbs)){
            return $this->add($method,...$arguments);
        }
    }

}