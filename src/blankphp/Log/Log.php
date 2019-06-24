<?php


namespace Blankphp\Log;


use Blankphp\Application;
use Psr\Log\AbstractLogger;

class Log extends AbstractLogger
{
    protected $handler;
    protected $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
        $handler = $app['config']->get('app.log_driver');
        $this->handler= new $handler;
    }

    public function log($level, $message, array $context = array())
    {
        //判断等级选择对应方法

        //存储到对应的message 和content

        //结束

    }

}