<?php


namespace Blankphp\Log;


use Blankphp\Application;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class Log extends AbstractLogger
{
    protected $handler;
    protected $nameSpace = "Blankphp\Log\Driver";

    public function __construct(Application $app)
    {
        $handler = $app['config']->get('app.log_driver');
        $this->handler = new $this->nameSpace . $handler;
    }

    public function log($level, $message, array $context = array())
    {
        //存储到对应的message 和content
        $this->handler->{$level}($message, $context);

    }

}