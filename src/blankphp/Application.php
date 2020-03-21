<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 19:32
 */

namespace BlankPhp;


use BlankPhp\Cache\Cache;
use BlankPhp\Config\Config;
use BlankPhp\Config\LoadConfig;
use BlankPhp\Contract\CookieContract;
use BlankPhp\Cookie\Cookie;
use BlankPhp\Database\Database;
use BlankPhp\Database\Grammar\Grammar;
use BlankPhp\Database\Grammar\MysqlGrammar;
use BlankPhp\Exception\Error;
use BlankPhp\Exception\NotFoundClassException;
use BlankPhp\Factory\FactoryBase;
use BlankPhp\Kernel\ConsoleKernel;
use BlankPhp\Kernel\HttpKernel;
use BlankPhp\Log\Log;
use BlankPhp\Provider\RegisterProvider;
use BlankPhp\Request\Request;
use BlankPhp\Response\Response;
use BlankPhp\Route\Route;
use BlankPhp\Route\Router;
use BlankPhp\Scheme\Scheme;
use BlankPhp\Session\Session;
use BlankPhp\View\StaticView;
use BlankPhp\View\View;

class Application extends Container
{

    private $version = '0.1.3-dev';

    protected $boot = false;

    protected $bootstraps = [
        LoadConfig::class => 'load',
        Error::class => 'register',
        RegisterProvider::class => 'register',
    ];

    public static function init()
    {
        return self::getInstance();
    }

    protected function __construct()
    {
        $this->registerService();
        $this->registerBase();
        $this->registerDirName();
        $this->bootstrap();
    }

    public function bootstrap()
    {
        if ($this->boot) {
            return;
        }
        foreach ($this->bootstraps as $provider => $method) {
            $this->call($provider, $method, null, [$this]);
        }
        $this->boot = true;
    }

    public function registerDirName()
    {
        define('PUBLIC_PATH', APP_PATH . DIRECTORY_SEPARATOR . 'public/');
    }

    public function registerService()
    {
        $temp = [
            'kernel' => [\BlankPhp\Contract\Kernel::class, HttpKernel::class],
            'console' => ConsoleKernel::class,
            'request' => [\BlankPhp\Contract\Request::class, Request::class],
            'route' => [\BlankPhp\Contract\Route::class, Route::class],
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
            'redis' => [Redis::class],
            'log' => Log::class
        ];
        array_walk($temp, array($this, 'bind'));
        unset($temp);
    }


    public function make($abstract, $parameters = [])
    {
        if (!$this->has($abstract)) {
            if (class_exists($abstract) && !empty($parameters)) {
                return $this->instance($abstract, new $abstract(...$parameters));
            }
        }
        return parent::make($abstract, $parameters);
    }


    public function registerBase()
    {
        $this->instance('app', $this);
        static::$instance = $this;
    }


    public function getSignal($abstract, $name = '')
    {
        if (empty($name)) {
            return isset($this->signal[$abstract]) ? $this->signal[$abstract] : [];
        }

        return isset($this->signal[$abstract][$name]) ? $this->signal[$abstract][$name] : [];
    }

    public function unsetSignal($abstract)
    {
        unset($this->signal[$abstract]);
    }


}


