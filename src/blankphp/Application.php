<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 19:32
 */

namespace Blankphp;


use Blankphp\Cache\Cache;
use Blankphp\Cache\Driver\File;
use Blankphp\Cache\Driver\Redis;
use Blankphp\Config\Config;
use Blankphp\Contract\CookieContract;
use Blankphp\Cookie\Cookie;
use Blankphp\Database\Database;
use Blankphp\Database\Grammar\Grammar;
use Blankphp\Database\Grammar\MysqlGrammar;
use Blankphp\Exception\NotFoundClassException;
use Blankphp\Kernel\HttpKernel;
use Blankphp\Log\Log;
use Blankphp\Request\Request;
use Blankphp\Response\Response;
use Blankphp\Route\Route;
use Blankphp\Route\Router;
use Blankphp\Scheme\Scheme;
use Blankphp\Session\Session;
use Blankphp\View\StaticView;
use Blankphp\View\View;

class Application extends Container
{

    private $version="0.1.0";

    public static function init()
    {
        return self::getInstance();
    }

    public function __construct()
    {
        //注册号一些服务
        $this->registerBase();
//        $this->registerSomeDir();
        $this->registerService();
        $this->registerProviders();
    }

    public function registerService()
    {
        $temp = [
            'kernel' => [\Blankphp\Contract\Kernel::class, HttpKernel::class],
            'request' => [\Blankphp\Contract\Request::class, Request::class],
            'route' => [\Blankphp\Contract\Route::class, Route::class],
            'router' => [Router::class],
            'app' => [\Blankphp\Contract\Container::class, Application::class],
            'db' => Database::class,
            'db.grammar' => [Grammar::class, MysqlGrammar::class],
            'view' => [\Blankphp\Contract\View::class, View::class],
            'view.static' => StaticView::class,
            'cookie' => [CookieContract::class, Cookie::class],
            'config' => Config::class,
            'session' => [\Blankphp\Contract\Session::class, Session::class],
            'scheme' => Scheme::class,
            'response' => Response::class,
            'cache' => [Cache::class],
            'cache.drive' => [Cache::class],
            'redis' => [Redis::class],
            'log' => Log::class
        ];
        array_walk($temp, array($this, "bind"));
    }


    public function make($abstract, $parameters = [])
    {
        if (!$this->has($abstract))
            if (class_exists($abstract))
                return new $abstract(...$parameters);
        return parent::make($abstract, $parameters);
    }

    //宏定义目录
    public function registerSomeDir()
    {
        //获取当前目录

        //获取根目录

        //定义目录
    }


    public function registerBase()
    {
        $this->instance('app', $this);
    }


    public function registerProviders()
    {

    }

    public function getSignal($abstract, $name = '')
    {
        if (empty($name)) {
            return isset($this->signal[$abstract]) ? $this->signal[$abstract] : [];
        } else {
            return isset($this->signal[$abstract][$name]) ? $this->signal[$abstract][$name] : [];
        }
    }

    public function unsetSignal($abstract)
    {
        unset($this->signal[$abstract]);
    }


}


