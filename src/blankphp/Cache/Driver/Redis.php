<?php


namespace Blankphp\Cache\Driver;


use Blankphp\Cache\Contract\Driver;

class Redis implements Driver
{
    //连接存储
    private $redis;

    public function __construct()
    {
        //初始化连接

        //
    }

    public function set($key, $value, $ttl = null)
    {
        // TODO: Implement set() method.
    }

    public function get($key, $default)
    {
        // TODO: Implement get() method.
    }

    public function remember($array, \Closure $closure)
    {
        // TODO: Implement remember() method.
    }

    public function has($key)
    {
        // TODO: Implement has() method.
    }


}