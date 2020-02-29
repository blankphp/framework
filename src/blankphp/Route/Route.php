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
use Blankphp\Route\Exception\NotFoundRouteException;
use Blankphp\Route\Exception\RouteErrorException;
use Blankphp\Route\Traits\SetMiddleWare;
use Blankphp\Route\Traits\ResolveSomeDepends;
use Helpers\Str;

//后期应该使用迭代器模式来进行优化
class Route implements Contract
{
    use ResolveSomeDepends, SetMiddleWare;
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
    //当前路由
    protected $currentRoute;
    //application
    private $app;
    //控制器命名空间
    protected $controllerNamespace;
    //路由前缀
    protected $prefix;
    //组
    protected $group;
    //中间件组
    protected $groupMiddleware;

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

    public function addRoute($methods, $uri, $action)
    {
        $uri = empty($this->prefix) ? '/' . trim($uri, '/') : '/' . trim($this->prefix, '/') . trim($uri, '/');
        $this->currentRoute = new RouteRule();
        $this->currentRoute->set($methods, $uri, $action, '', $this->group, $this->groupMiddleware);
        $this->routes->add($this->currentRoute, $this->currentRoute->getRule(), $methods);
        return $this->currentRoute;
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
        $this->groupMiddleware = $group;
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
        //先匹配uri
        foreach ($this->routes as $keys => $route) {
            if (preg_match("#^$keys$#", $uri, $match)) {
                if (isset($route[$method])) {
                    array_shift($match);
                    $route = $route[$method];
                    $route->setVars($match);
                    return $route;
                } else {
                    throw new HttpException("This Route is not allowed [{$method}]", 405);
                }
            }
        }
        throw new HttpException("Not Found this route[{$uri}]", 404);
    }

    public function name()
    {

    }

    public function findRoute($request)
    {
        $current = $this->match($request);
        if (!empty($current)) {
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
        $controllerName = Str::makeClassName($controller[0], $this->controllerNamespace . '\\');
        $method = $controller[1];
        if (!is_null($controllerName) || !is_null($method))
            return array($controllerName, $method);
        throw new RouteErrorException('控制器方法错误');
    }


    public function runController($controller, $method, $parameters = [])
    {
        $parameters = $this->resolveClassMethodDependencies(
            $parameters, $controller, $method
        );
        if ($controller === 'Closure')
            return $method(...array_values($parameters));
        //解决方法的依赖
        $controller = Application::getInstance()->build($controller);
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