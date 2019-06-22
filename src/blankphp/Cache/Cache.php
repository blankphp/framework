<?php


namespace Blankphp\Cache;


use Blankphp\Application;

class Cache extends CacheAbstract
{
    protected $tag;

    protected static $dir = APP_PATH . 'cache/framework/';

    protected $option=[
        'file'=>'',
        'table'=>'',
        '',
    ];

    public function __construct(Application $app)
    {
        $handler = config('app.cache.driver');
        $this->setHandler(new $handler);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        //调用驱动的方法
        $this->getHandler()->$name(...$arguments);
    }

}