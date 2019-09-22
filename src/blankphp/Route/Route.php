<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 21:32
 */

namespace Blankphp\Route;

use Blankphp\Application;
use Blankphp\Cache\Driver\File;
use Blankphp\Collection\Collection;
use Blankphp\Contract\Route as Contract;
use Blankphp\Exception\HttpException;
use Blankphp\Route\Traits\SetMiddleWare;
use Blankphp\Route\Traits\ResolveSomeDepends;

//后期应该使用迭代器模式来进行优化
class Route implements Contract
{
    use ResolveSomeDepends,SetMiddleWare;
    /**
     * @var array
     * 请求方式
     */
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
    /**
     * @var Collection
     * 路由集合
     */
    protected $routes;
    /**
     * @var RouteRule
     * 当前的路由规则
     */
    protected $currentRoute;
    protected $app;
    protected $container;
    protected $controllerNamespace;
    protected $prefix;
    protected $group;
    protected $groupMiddleware;
    protected $current = [];

    public function __construct(Application $app, RuleCollection $collection)
    {
        $this->app = $app;
        $this->routes = $collection;
    }

    public function get($uri, $action)
    {
        return $this->addRoute(['GET'], $uri, $action);
    }

    public function delete($uri, $action)
    {
        return $this->addRoute(['DELETE'], $uri, $action);
    }

    public function put($uri, $action)
    {
        return $this->addRoute(['PUT'], $uri, $action);
    }

    public function post($uri, $action)
    {
        return $this->addRoute(['POST'], $uri, $action);
    }

    public function any($uri, $action)
    {
        return $this->addRoute(self::$verbs, $uri, $action);
    }

    public function addRoute($methods, $uri, $action)
    {
        $uri = empty($this->prefix) ? $uri : '/' . ltrim($this->prefix, '/') . $uri;
        $this->currentRoute = new RouteRule();
        $this->currentRoute->set($methods, $uri, $action,'',$this->group,$this->groupMiddleware);
        $this->routes->item($this->currentRoute);
        return $this;
    }

    public function middleware()
    {
        //引用中间件别名[然后获取]
        $middleware = func_get_args();
        $middleware = array_filter($middleware);
        $this->currentRoute->setMiddleware($middleware);
        return $this;
    }


    public function file($file)
    {
        require $file;
    }

    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function group($group)
    {
        $this->group = $group;
        return $this;
    }

    public function GroupMiddleware($groupMiddleware)
    {
        $this->groupMiddleware = $groupMiddleware;
        return $this;
    }


    public function setNamespace($namespace)
    {
        $this->controllerNamespace = $namespace;
        return $this;
    }

    public function match($request)
    {
        //判断方法
        $method = $request->method;
        //获取访问的uri
        $uri = $request->uri;
        $this->routes->toArray();
        if (isset($this->routes[$uri][$method])) {
            $this->current = $this->routes[$uri][$method][0];
        }
    }

    public function name(){

    }

    public function findRoute($request)
    {
        $this->match($request);
        if (!empty($this->current)) {
            //获取控制器
            $controller = $this->getController($this->current['action']);
            $this->setMiddleWare($this->current['middleware']);
            return $controller;
        }
        throw new HttpException('该路由暂无控制器', 5);
    }

    public function getController($controller)
    {
        //如过传递的是闭包
        if ($controller instanceof \Closure)
            return array('Closure', $controller);
        //如果不是闭包
        $controller = explode('@', $controller);
        $controllerName = $this->controllerNamespace . '\\' . $controller[0];
        $method = $controller[1];
        if (!is_null($controllerName) || !is_null($method))
            return array($controllerName, $method);
        throw new \Exception('控制器方法错误', 4);
    }


    public function runController($controller, $method, $parameters = [])
    {
        $parameters = $this->resolveClassMethodDependencies(
            $parameters, $controller, $method
        );
        if ($controller === 'Closure')
            return $method(...array_values($parameters));
        //解决方法的依赖
        $controller = $this->app->build($controller);
        //获取控制器的对象,返回结果
        return $controller->{$method}(...array_values($parameters));
    }


    public function run($request)
    {
        return $this->runController(...$this->findRoute($request));
    }

    public function putCache()
    {
        File::putCache($this->routes, 'route.php');
    }

    public function getCache()
    {
        if (is_file(APP_PATH . '/cache/framework/route.php')) {
            $this->routes = include APP_PATH . '/cache/framework/route.php';
            return false;
        }
        return true;
    }

    public function parseVar()
    {
        //转换为普通变量

    }

    public function parseModel()
    {
        //绑定模型变量

    }
}