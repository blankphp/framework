<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp;

use BlankPhp\Cache\Cache;
use BlankPhp\Config\Config;
use BlankPhp\Contract\CookieContract;
use BlankPhp\Cookie\Cookie;
use BlankPhp\Database\Database;
use BlankPhp\Database\Grammar\Grammar;
use BlankPhp\Database\Grammar\MysqlGrammar;
use BlankPhp\Kernel\ConsoleKernel;
use BlankPhp\Log\Log;
use BlankPhp\Request\Parse;
use BlankPhp\Request\Request;
use BlankPhp\Response\Response;
use BlankPhp\Route\Route;
use BlankPhp\Route\RouteCollection;
use BlankPhp\Route\Router;
use BlankPhp\Scheme\Scheme;
use BlankPhp\Session\Session;
use BlankPhp\View\StaticView;
use BlankPhp\View\View;

class Application extends Container
{
    private $version = '0.2.3-dev';

    /**
     * @var Application
     */
    protected static $instance;

    public static function init()
    {
        return self::getInstance();
    }

    protected function __construct()
    {
        parent::__construct();
        $this->registerDirName();
        $this->registerBaseService();
        $this->registerBase();
    }

    /**
     * 单例模式.
     *
     * @return Application
     */
    public static function getInstance()
    {
        //Unsafe usage of new static()
        if (empty(static::$instance)) {
            new static();
        }

        return static::$instance;
    }

    public function registerDirName()
    {
        define('DS', DIRECTORY_SEPARATOR);
        if (!defined('APP_PATH')) {
            define('APP_PATH', dirname(dirname(__DIR__)));
        }
        define('PUBLIC_PATH', APP_PATH.DS.'public/');
        define('CONFIG_PATH', APP_PATH.DS.'config');
    }

    public function registerBaseService()
    {
        foreach ([
                     'console' => ConsoleKernel::class,
                     'request' => [\BlankPhp\Contract\Request::class, Request::class],
                     'request.parse' => [Parse::class],
                     'route' => [\BlankPhp\Contract\Route::class, Route::class],
                     'route.collection' => [RouteCollection::class],
                     'router' => [Router::class],
                     'app' => [\BlankPhp\Contract\Container::class, __CLASS__],
                     'db' => Database::class,
                     'db.grammar' => [Grammar::class, MysqlGrammar::class],
                     'view' => [\BlankPhp\Contract\View::class, View::class],
                     'view.static' => StaticView::class,
                     'cookie' => [CookieContract::class, Cookie::class],
                     'config' => Config::class,
                     'session' => [\BlankPhp\Contract\Session::class, Session::class],
                     'scheme' => Scheme::class,
                     'response' => Response::class,
                     'cache' => [Cache::class],
                     'cache.drive' => [Cache::class],
//                     'redis' => [Redis::class],
                     'log' => Log::class,
                 ] as $k => $v) {
            $this->bind($k, $v);
        }
    }

    /**
     * @param $abstract
     * @param $parameters
     *
     * @return mixed|void
     *
     * @throws Exception\ParameterLoopException
     * @throws \ReflectionException
     */
    public function make($abstract, $parameters = [])
    {
        if (!$this->has($abstract)) {
            if (class_exists($abstract) && !empty($parameters)) {
                return new $abstract(...$parameters);
            }
        }

        return parent::make($abstract, $parameters);
    }

    /**
     * @throws \ReflectionException
     * @throws Exception\ParameterLoopException
     */
    public function signal($abstract, $instance)
    {
        $this->bind($abstract, $instance);

        return $this->make($abstract);
    }

    public function registerBase()
    {
        $this->instance('app', $this);
        static::$instance = $this;
    }
}
