<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/1
 * Time: 18:53
 * 框架的核心文件
 */

namespace BlankPhp\Kernel;

use BlankPhp\Application;
use BlankPhp\Config\Config;
use BlankPhp\Config\LoadConfig;
use BlankPhp\Contract\Kernel;
use BlankPhp\Exception\Error;
use BlankPhp\Provider\RegisterProvider;
use BlankPhp\Route\Router;

class HttpKernel
{
    protected $config = [];
    protected $app;
    protected $route;

    public function startConfig($config)
    {
        //处理设置
    }

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->route = $app->make('router');
    }

    public function registerRequest($request)
    {
        $this->app->instance('request', $request);
    }

    //处理请求===》返回一个response，这里交给route组件
    public function handle($request)
    {
        $this->startConfig($this->config);
        $this->registerRequest($request);
        return $this->route->dispatcher($request);
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