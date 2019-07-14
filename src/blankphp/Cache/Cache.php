<?php


namespace Blankphp\Cache;


use Blankphp\Application;
use Blankphp\Contract\Container;

class Cache extends CacheAbstract
{
    protected $tag;

    protected static $dir = APP_PATH . 'cache/framework/';

    protected $option=[
        'file'=>'',
        'table'=>'',
        'handler'=>'Blankphp\Cache\Driver\\',
    ];

    public function __construct()
    {
        $handler = config('app.cache.driver');
        $handler=$this->option['handler'].ucfirst(strtolower($handler));
        $this->setHandler(new $handler($this->option));
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        //调用驱动的方法
        $this->getHandler()->$name(...$arguments);
    }

}