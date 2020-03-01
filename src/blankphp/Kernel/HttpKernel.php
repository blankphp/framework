<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/1
 * Time: 18:53
 * 框架的核心文件
 */

namespace Blankphp\Kernel;

use Blankphp\Application;
use Blankphp\Config\Config;
use Blankphp\Config\LoadConfig;
use Blankphp\Contract\Kernel;
use Blankphp\Exception\Error;
use Blankphp\Provider\RegisterProvider;
use Blankphp\Route\Router;

class HttpKernel
{
    protected $config = [];
    protected $app;
    protected $route;

    protected $bootstraps = [
        LoadConfig::class => 'load',
        Error::class => 'register',
        RegisterProvider::class => 'register',
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->route = $app->make("router");
    }

    public function registerRequest($request)
    {
        $this->app->instance('request', $request);
    }

    //处理请求===》返回一个response，这里交给route组件
    public function handle($request)
    {
        //注册服务
        $this->bootstrap();
        $this->registerRequest($request);
        return $this->route->dispatcher($request);
    }


    public function bootstrap()
    {
        //引导框架运行
        foreach ($this->bootstraps as $provider => $method) {
            $this->app->call($provider, $method, [$this->app]);
        }
    }

    public function registerService($bootstrap)
    {
        $this->app->make($bootstrap);
    }


    public function flush()
    {
        $this->app->flush();
    }


}