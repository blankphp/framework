<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 19:32
 */

namespace Blankphp;


use Blankphp\Cache\Cache;
use Blankphp\Config\Config;
use Blankphp\Config\LoadConfig;
use Blankphp\Contract\CookieContract;
use Blankphp\Cookie\Cookie;
use Blankphp\Database\Database;
use Blankphp\Database\Grammar\Grammar;
use Blankphp\Database\Grammar\MysqlGrammar;
use Blankphp\Exception\Error;
use Blankphp\Exception\NotFoundClassException;
use BlankPhp\Factory\FactoryBase;
use Blankphp\Kernel\ConsoleKernel;
use Blankphp\Kernel\HttpKernel;
use Blankphp\Log\Log;
use Blankphp\Provider\RegisterProvider;
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
            'kernel' => [\Blankphp\Contract\Kernel::class, HttpKernel::class],
            'console' => ConsoleKernel::class,
            'request' => [\Blankphp\Contract\Request::class, Request::class],
            'route' => [\Blankphp\Contract\Route::class, Route::class],
            'router' => [Router::class],
            'app' => [\Blankphp\Contract\Container::class, __CLASS__],
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


