<?php


namespace Blankphp\Kernel;


use Blankphp\Application;
use Blankphp\Config\LoadConfig;
use Blankphp\Console\Args;
use Blankphp\Console\Cache\CacheConsole;
use Blankphp\Console\Cache\MakeConsole;
use Blankphp\Console\Cache\PublishConsole;
use Blankphp\Contract\Kernel;
use Blankphp\Exception\Error;
use Blankphp\Provider\RegisterProvider;
use Blankphp\Response\Response;
use Blankphp\Route\Router;

class ConsoleKernel implements Kernel
{
    private $version = "0.1.1";

    protected $app;
    protected $command = [
        'make' => MakeConsole::class,
        'cache' => CacheConsole::class,
        'publish' => PublishConsole::class
    ];
    protected $bootstraps = [
        LoadConfig::class => 'load',
        Error::class => 'register',
        RegisterProvider::class => 'register',
    ];
    protected $args = [];

    public function __construct(Application $app, $command = [])
    {
        $this->app = $app;
        $this->command = array_merge($this->command, $command);
    }

    public function bootstrap()
    {
        //引导框架运行
        foreach ($this->bootstraps as $provider => $method) {
            $this->app->call($provider, $method, [$this->app]);
        }
    }


    public function handle($args)
    {
        //获取参数
        $this->bootstrap();
        $this->registerArgs($args);
        return $this->dispatcher($args);
    }

    public function dispatcher($args)
    {
        //推送到指定类
        if (isset($this->command[$args[0]])) {
            //开始实例化
            $command = $this->app->build($this->command[$args[0]]);
            switch (count($args)) {
                case 0:
                case 1:
                    $result = $this->tips($args);
                    break;
                case 2:
                    $result = $command->{$args[1]}();
                    break;
                default:
                    $method = $args[1];
                    array_shift($args);
                    $result = $command->{$method}(...$args);
                    break;
            }
        }
        echo $result;
    }

    public function tips($command){
        //提示
    }

    public function registerArgs($args)
    {
        $this->args = $args;
    }


    public function flush()
    {
        $this->route->flush();
        $this->app->flush();
    }


}