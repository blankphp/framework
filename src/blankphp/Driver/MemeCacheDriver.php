<?php


namespace BlankPhp\Driver;


use BlankPhp\Facade\Application;
use Predis\Client;

class MemeCacheDriver extends Driver
{


    public function __construct($name = 'default', $option = [])
    {
        parent::__construct($name, $option);
    }

    public function set($key, $value, $ttl = null)
    {
        // TODO: Implement set() method.
    }

    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    public function get($key, $default = '')
    {
        // TODO: Implement get() method.
    }

    public function remember($array, \Closure $closure, $ttl = 0)
    {
        // TODO: Implement remember() method.
    }

    public function has($key)
    {
        // TODO: Implement has() method.
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }

    public function forget($key)
    {
        // TODO: Implement forget() method.
    }
}