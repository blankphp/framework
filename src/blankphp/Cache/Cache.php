<?php


namespace Blankphp\Cache;


use Blankphp\Application;
use Blankphp\Contract\Container;
use Helpers\Str;

class Cache extends CacheAbstract
{
    protected $tag;

    protected $option = [
        'nameSpace' => 'Blankphp\Cache\Driver\\',
        'driver' => 'file',
    ];


    public function __construct()
    {
        $this->setOption(config('cache'));
        $driver = $this->option['driver'];
        $handler = Str::makeClassName($driver, $this->option['nameSpace']);
        $this->setHandler($handler::getInstance($this->option[$driver]));
    }

    public function setOption($config)
    {
        $this->option = array_merge($this->option, $config);
    }

    public function __call($name, $arguments)
    {
        $this->getHandler()->$name(...$arguments);
    }

}