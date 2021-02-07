<?php


namespace BlankPhp\Kernel;


use BlankPhp\Application;
use BlankPhp\Config\LoadConfig;
use BlankPhp\Console\Args;
use BlankPhp\Console\Cache\CacheConsole;
use BlankPhp\Console\Make\MakeConsole;
use BlankPhp\Console\Publish\PublishConsole;
use BlankPhp\Contract\Kernel;
use BlankPhp\Exception\Error;
use BlankPhp\Provider\RegisterProvider;
use BlankPhp\Response\Response;
use BlankPhp\Route\Router;

class ConsoleKernel implements Kernel
{
    private $version = '0.1.2';

    protected $app;

    protected $command = [
        'make' => MakeConsole::class,
        'cache' => CacheConsole::class,
        'publish' => PublishConsole::class
    ];

    protected $args = [];
    protected $config = [];


    public function __construct(Application $app, $command = [])
    {
        $this->app = $app;
        $this->command = array_merge($this->command, $command);
    }

    public function startConfig($config)
    {
        //处理设置
    }

    public function handle($args)
    {
        //获取参数
        $this->registerArgs($args);
        return $this->dispatcher($args);
    }

    public function dispatcher($args)
    {
        //推送到指定类
        if (isset($this->command[$args[0]])) {
            //开始实例化
            $command = new $this->command[$args[0]]($this->app);
            switch (count($args)) {
                case 0:
                case 1:
                    $result = $this->tips($args);
                    break;
                case 2:
                    $method = $args[1];
                    $result = $command->{$method}();
                    break;
                default:
                    $method = $args[1];
                    array_shift($args);
                    array_shift($args);
                    $result = $command->{$method}(...$args);
                    break;
            }
            echo $result;
        }
    }

    public function tips($command)
    {
        //提示
    }

    public function registerArgs($args): void
    {
        $this->args = $args;
    }


    public function flush(): void
    {
        $this->route->flush();
        $this->app->flush();
    }


}