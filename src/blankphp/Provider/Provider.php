<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/12
 * Time: 10:14
 */

namespace BlankPhp\Provider;


use BlankPhp\Application;
use BlankPhp\Contract\ProviderContract;

class Provider implements ProviderContract
{
    protected $app;
    /**
     * @var array
     */
    protected $command = [];
    /**
     * @var array
     */
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

    public function boot(){

    }

}