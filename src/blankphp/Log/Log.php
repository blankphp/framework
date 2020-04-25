<?php


namespace BlankPhp\Log;


use BlankPhp\Application;
use BlankQwq\Helpers\Str;
use Psr\Log\AbstractLogger;

class Log extends AbstractLogger
{
    protected $handler;

    protected $nameSpace = "BlankPhp\Log\Driver";

    public function __construct(Application $app)
    {
        $handler = $app['config']->get('app.log_driver', 'File');
        $handler = Str::makeClassName($handler, $this->nameSpace);
        $this->handler = new $handler;
    }

    public function log($level, $message, array $context = array())
    {
        //存储到对应的message 和content
        $this->handler->log($level, $message, $context);
    }

}