<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/12
 * Time: 10:14
 */

namespace Blankphp\Provider;


use Blankphp\Application;
use Blankphp\Contract\ProviderContract;

class Provider implements ProviderContract
{
    protected $app;
    /**
     * @var array
     * 命令行注册
     */
    protected $command = [];
    //关联配置文件映射
    protected $config = [];

    public function __construct()
    {
        $this->app = Application::getInstance();
        if (method_exists($this, 'boot')) {
            $this->boot();
        }
        if (method_exists($this, 'register')) {
            $this->register();
        }
    }

    /**
     * @return void
     */
    public function boot()
    {

    }

    /**
     * @return void
     */
    public function register()
    {

    }

}