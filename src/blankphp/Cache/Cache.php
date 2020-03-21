<?php


namespace Blankphp\Cache;


use Blankphp\Application;
use Blankphp\Contract\Container;
use Blankphp\Facade\Driver;
use BlankQwq\Helpers\Str;

class Cache extends CacheAbstract
{
    protected $tag;


    public function __construct()
    {
        $this->setOption(config('cache'));
        $driver = $this->config['driver'];
        $handler = Driver::factory($driver, "cache");
        $this->setHandler($handler);
    }

    public function setOption($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    public function __call($name, $arguments)
    {
        return $this->getHandler()->$name(...$arguments);
    }

}