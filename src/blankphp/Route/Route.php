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
use BlankPhp\Contract\Route as Contract;
use BlankPhp\Exception\HttpException;
use BlankQwq\Helpers\Arr;
use BlankQwq\Helpers\File;
use BlankQwq\Helpers\Re;
use BlankPhp\Route\Exception\NotFoundRouteException;
use BlankPhp\Route\Exception\RouteErrorException;
use BlankPhp\Route\Traits\SetMiddleWare;
use BlankPhp\Route\Traits\ResolveSomeDepends;
use BlankQwq\Helpers\Str;

//后期应该使用迭代器模式来进行优化
class Route implements Contract
{
    use ResolveSomeDepends, SetMiddleWare;
    /**
     * @var array
     * 请求方式
     */
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
    //路由集合
    protected $routes;
    //当前路由
    protected $currentRoute;
    /**
     * @var Application
     */
    private $app;
    //控制器命名空间
    protected $controllerNamespace;
    //路由前缀
    protected $prefix;
    //组
    protected $group;
    //中间件组
    protected $groupMiddleware;
    //文件队列
    protected $files = [];
    //已经加载过的文件
    protected $loadedFiles = [];
    //锁住打开文件器
    private $lock = false;

    public function __construct()
    {
        $this->routes = new RuleCollection();
        $this->app = Application::getInstance();
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

    public function func($config, \Closure $closure): void
    {
        $key_set = ['namespace', 'prefix', 'group'];
        $temp = [];
        foreach ($config as $key => $value) {
            //开始设置
            if (in_array($key, $key_set, true)) {
                $temp[$key] = $this->{'get' . ucfirst($key)}();
                $this->{'set' . ucfirst($key)}($value, $temp[$key]);
            }
        }
        $closure($this);
        foreach ($temp as $key => $value) {
            $this->{'get' . ucfirst($key)}();
            $this->{'set' . ucfirst($key)}($value);
        }
    }


    public function addRoute($methods, $uri, $action)
    {
        $uri = empty($this->prefix[0]) ? '/' . trim($uri, '/') : '/' . trim($this->prefix[0], '/') . '/' . trim($uri, '/');
        $this->currentRoute = new RouteRule();
        $this->currentRoute->set($methods, strlen($uri) === 1 ? $uri : rtrim($uri, '/'), trim($this->controllerNamespace, '\\') . '\\' . $action, '', $this->group[0], $this->prefix[0]);
        $this->routes->add($this->currentRoute, $this->currentRoute->getRule(), $methods);
        return $this->currentRoute;
    }


    public function file($file)
    {
        if ($this->lock) {
            return;
        }
        if (is_file($cache = config('cache.file.route'))) {
            $this->routes = require $cache;
            $this->lock = true;
            return;
        }
        $this->files[] = $file;
        if (count($this->prefix) === 0) {
            $this->prefix();
        }
        if (count($this->group) === 0) {
            $this->group();
        }
        if (count($this->files) > 1) {
            $this->loadNextFile(false);
        }
    }

    public function prefix($prefix = '')
    {
        $this->prefix[] = $prefix;
        return $this;
    }

    public function group($group): Route
    {
        $this->group[] = $group;
        $this->groupMiddleware[] = $group;
        return $this;
    }


    public function setNamespace($namespace, $parent = ''): Route
    {
        $this->controllerNamespace = empty($namespace) ? $namespace : $parent . '\\' . $namespace;
        return $this;
    }

    public function match($request)
    {
        //判断方法
        $method = $request->method;
        //获取访问的uri
        $uri = $request->uri;
        //先匹配uri
        While (True) {
            foreach ($this->routes as $keys => $route) {
                if (preg_match("#^$keys$#", $uri, $match)) {
                    if (isset($route[$method])) {
                        return $this->getRoute($match, $method, $route);
                    }
                    throw new HttpException("This Route is not allowed [{$method}]", 405);
                }
            }
            if (count($this->files) <= 0) {
                break;
            }
            $this->loadNextFile();
        }
        throw new HttpException("Not Found this route[{$uri}]", 404);
    }


    public function getRoute($match, $method, &$route)
    {
        array_shift($match);
        $route = $route[$method];
        if (is_array($route)) {
            //数组类型路由如何
            $temp = new RouteRule();
            $temp->fromArray($route);
            $route = $temp;
        }
        $route->setVars($match);
        return $route;
    }


    public function loadNextFile($shift = true): void
    {
        $file = $this->loadedFile(array_shift($this->files));
        $this->routes = new RuleCollection();
        if ($shift) {
            array_shift($this->group);
            array_shift($this->prefix);
        }
        require $file;
        unset($file);
    }

    public function loadedFile($file)
    {
        return $this->loadedFiles[] = $file;
    }

    public function findRoute($request)
    {
        $current = $this->match($request);
        if ($current !== null) {
            //获取控制器
            $controller = $this->getController($current->action);
            $current->setAction($controller);
            return $current;
        }
        throw new HttpException("Not Found this route controller [{$current->action}]", 404);
    }

    public function getController($controller)
    {
        //如过传递的是闭包
        if ($controller instanceof \Closure)
            return array('Closure', $controller);
        //如果不是闭包
        $controller = explode('@', $controller);
        [$controllerName, $method] = $controller;
        if ($controllerName !== null || $method !== null) {
            return array($controllerName, $method);
        }
        throw new RouteErrorException('控制器方法错误');
    }


    public function runController($controller, $method, $parameters = [])
    {
        $parameters = $this->resolveClassMethodDependencies(
            $parameters, $controller, $method
        );
        if ($controller === 'Closure') {
            return $method(...array_values($parameters));
        }
        //解决方法的依赖
        $controller = Application::getInstance()->build($controller);
        //获取控制器的对象,返回结果
        return $controller->{$method}(...array_values($parameters));
    }

    public function run($request)
    {
        try {
            return $this->runController(...$this->findRoute($request));
        } catch (HttpException $e) {
        }
    }

    public function putCache()
    {
        File::putCache($this->routes, 'route.php');
    }

    public function cache(): array
    {
        $cache = [];
        while (True) {
            $cache = Arr::merge($cache, $this->routes->toArray());
            if (count($this->files) === 0) {
                break;
            }
            $this->loadNextFile();
        }
        return $cache;
    }


    public function getNamespace(): string
    {
        return $this->controllerNamespace;
    }

    public function setPrefix($prefix, $parent = ''): void
    {
        array_unshift($this->prefix, (empty($parent) ? '/' : '/' . trim($parent, '/') . '/') . $prefix);
    }

    public function getPrefix(): string
    {
        return array_shift($this->prefix);
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