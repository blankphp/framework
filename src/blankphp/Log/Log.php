<?php


namespace Blankphp\Log;


use Blankphp\Application;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class Log extends AbstractLogger
{
    protected $handler;
    protected $app;
    protected $nameSpace="Blankphp\Log\Driver";

    public function __construct(Application $app)
    {
        $this->app = $app;
        $handler = $app['config']->get('app.log_driver');
        $this->handler= new $this->nameSpace.$handler;
    }

    public function log($level, $message, array $context = array())
    {
        //判断等级选择对应方法

        $this->handler->{$level}($message,$context);
        //存储到对应的message 和content

        //结束

    }

}