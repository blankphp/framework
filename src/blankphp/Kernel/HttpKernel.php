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
use BlankPhp\Bootstrap\LoadConfig;
use BlankPhp\Bootstrap\LoadEnv;
use BlankPhp\Bootstrap\RegisterProvider;
use BlankPhp\Contract\Kernel;
use BlankPhp\Route\Router;

class HttpKernel implements Kernel
{
    /** @var Application */
    protected $app;
    /** @var Router */
    protected $router;
    /** @var bool */
    protected $boot = false;

    protected $bootstraps = [
        LoadEnv::class,
        LoadConfig::class,
        RegisterProvider::class,
    ];


    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @throws \ReflectionException
     */
    public function bootstrap()
    {
        if (!$this->boot) {
            foreach ($this->bootstraps as $bootstrap) {
                $this->app->call($bootstrap, 'boot', [$this->app]);
            }
            $this->router = $this->app->make("router");
            $this->boot = true;
        }
    }


    public function registerRequest($request)
    {
        $this->app->instance('request', $request);
    }

    //处理请求===》返回一个response，这里交给route组件
    public function handle($request)
    {
        try {
            $this->bootstrap();
            $this->registerRequest($request);
            return $this->router->dispatcher($request);
        } catch (\Throwable $exception) {
            // 函数处理
            var_dump($exception->getMessage(),$exception->getFile(),$exception->getLine());exit();
        }
    }


    public function flush()
    {
        $this->app->flush();
    }


}