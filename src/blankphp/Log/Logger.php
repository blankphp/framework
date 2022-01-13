<?php


namespace BlankPhp\Log;


use BlankPhp\Application;
use BlankPhp\Exception\NotFoundClassException;
use BlankQwq\Helpers\Str;
use Monolog\Formatter\LineFormatter;

class Logger
{
    protected $handler;
    protected $config = [
        'format'=>'[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n',
        'time_format'=>'Y-m-d H:i:s'
    ];
    private const NAME_SPACE = __NAMESPACE__ . '/Driver';

    public function __construct(Application $app)
    {
        $this->config = array_merge($this->config, $app['config']->get('log'));
        $this->handler = $this->getHandler();
    }

    private function getHandler(): Log
    {
        $channel = $this->config['default'];
        $config = $this->config[$channel];
        $driver = $this->config['driver'];
        $formatter =  new LineFormatter($this->config['format'], $this->config['time_format']);
        if (class_exists($driver)) {
            $driver =  new $driver($config);
        }else{
            $driver = $this->getDriver($config);
        }
        $driver->setFormatter($formatter);
        return $this->handler = $driver ;
    }


    private function getDriver($name)
    {
        $className = Str::makeClassName($name, self::NAME_SPACE);
        if (class_exists($className)) {
            // 生产对象
//            需要设置值

        }
        throw new NotFoundClassException($className);
    }

    private function formatFileName()
    {

    }

    public function log($level, $message, array $context = array()): void
    {
        $this->handler->log($level, $message, $context);
    }

}