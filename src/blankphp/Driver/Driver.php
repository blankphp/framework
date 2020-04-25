<?php


namespace BlankPhp\Driver;

use BlankPhp\Driver\Contract\Driver as DriverContract;
use BlankPhp\Driver\Traits\OtherHelpTrait;
use BlankPhp\Driver\Traits\SessionHandlerTrait;

abstract class Driver implements DriverContract, \SessionHandlerInterface
{
    use SessionHandlerTrait, OtherHelpTrait;

    /**
     * @param $value
     * @return string
     */
    public function parseValue($value)
    {
        return serialize($value);
    }

    /**
     * @param $value
     * @param array $option
     * @return mixed
     */
    public function valueParse($value, $option = [])
    {
        return unserialize($value, $option);
    }

    /**
     * @param $max_live_time
     * @return bool
     */
    public function clearExpireData($max_live_time): bool
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