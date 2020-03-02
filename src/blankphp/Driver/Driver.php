<?php


namespace Blankphp\Driver;

use Blankphp\Driver\Contract\Driver as DriverContract;

abstract class Driver implements DriverContract
{

    protected $gc = 35000;

    abstract public function __construct($name = "default", $option = []);

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        $this->delete($session_id);
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    public function open($save_path, $name)
    {
        return true;
    }

    public function read($session_id)
    {
        return $this->get($session_id);
    }

    public function write($session_id, $session_data)
    {
        $this->set($session_id, $session_data, $this->gc);
        //设置过期时间
    }


    abstract public function parseValue($value);

    abstract public function valueParse($value);

    abstract public function set($key, $value, $ttl = null);

    abstract public function delete($key);

    abstract public function get($key, $default = '');

    abstract public function remember($array, \Closure $closure, $ttl = 0);

    abstract public function has($key);

    abstract public function flush();

    abstract public function forget($key);
}