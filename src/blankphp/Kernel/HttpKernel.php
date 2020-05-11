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
    /**
     * @var array
     */
    protected $config = [];
    /**
     * @var Application
     */
    protected $app;
    /**
     * @var mixed|void|null
     */
    protected $route;



    /**
     * HttpKernel constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->route = $app->make('router');
    }

    public function startConfig($config): void
    {
        //处理设置
    }

    public function registerRequest($request): void
    {
        $this->app->instance('request', $request);
    }

    public function handle($request)
    {
        $this->startConfig($this->config);
        $this->registerRequest($request);
        return $this->route->dispatcher($request);
    }


    public function registerService($bootstrap): void
    {
        $this->app->make($bootstrap);
    }


    public function flush(): void
    {
        $this->app->flush();
    }


}