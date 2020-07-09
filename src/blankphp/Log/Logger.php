<?php


namespace BlankPhp\Log;


use BlankPhp\Application;
use BlankQwq\Helpers\Str;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as LoggerBase;
use Psr\Log\AbstractLogger;

class Logger
{
    protected $handler;
    protected $config = [
        'level' => 'debug',
    ];

    public function __construct(Application $app)
    {
        $this->config = array_merge($this->config, $app['config']->get('log'));
        $this->handler = $this->getHandler();
    }

    private function getHandler(): Log
    {
        $level = strtoupper($this->config['level']);
        return new Log(new StreamHandler($this->config['path'], $level));
    }

    private function formatFileName(){

    }

    public function log($level, $message, array $context = array()): void
    {
        $this->handler->log($level, $message, $context);
    }

}