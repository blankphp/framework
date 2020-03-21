<?php


namespace BlankPhp\Driver;

use BlankPhp\Driver\Contract\Driver as DriverContract;
use BlankPhp\Driver\Traits\OtherHelpTrait;
use BlankPhp\Driver\Traits\SessionHandlerTrait;

abstract class Driver implements DriverContract, \SessionHandlerInterface
{
    use SessionHandlerTrait, OtherHelpTrait;

     public function __construct($name = 'default', $option = []){

     }

    public function parseValue($value)
    {
        return serialize($value);
    }

    public function valueParse($value)
    {
        return unserialize($value);
    }

    public function clearExpireData($max_live_time)
    {
        return true;
    }


    abstract public function set($key, $value, $ttl = null);

    abstract public function delete($key);

    abstract public function get($key, $default = '');

    abstract public function remember($array, \Closure $closure, $ttl = 0);

    abstract public function has($key);

    abstract public function flush();

    abstract public function forget($key);
}