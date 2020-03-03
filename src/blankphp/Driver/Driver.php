<?php


namespace Blankphp\Driver;

use Blankphp\Driver\Contract\Driver as DriverContract;
use Blankphp\Driver\Traits\OtherHelpTrait;
use Blankphp\Driver\Traits\SessionHandlerTrait;

abstract class Driver implements DriverContract, \SessionHandlerInterface
{
    use SessionHandlerTrait, OtherHelpTrait;

    abstract public function __construct($name = "default", $option = []);

    public function parseValue($value)
    {
        return serialize($value);
    }

    public function valueParse($value)
    {
        return unserialize($value);
    }

    public function clearExpireData($maxlifetime)
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