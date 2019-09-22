<?php


namespace Blankphp\Cache\Driver;


use Blankphp\Application;
use Blankphp\Cache\Contract\Driver;
use Predis\Client;

class Redis implements Driver
{
    //连接存储
    private $redis;
    protected static $instance;

    public function __construct(Application $app)
    {
        $config = config('db.database.redis');
        //初始化连接
        $this->redis = new Client($config);
        $app->instance('redis', $this);
    }


    public static function getInstance(Application $app = null)
    {
        if (!empty(self::$instance)) {
            return self::$instance;
        } else {
            return self::$instance = new self($app);
        }
    }

    private function parseValue($value)
    {
        //把值转化为可存储的value
        if (is_string($value))
            return $value;
        else
            return $value;
    }

    public function set($key, $value, $ttl = null)
    {
        if (is_null($this))
            return $this->redis->set($key, $this->parseValue($value), $ttl);
        else
            return $this->redis->set($key, $this->parseValue($value));
    }

    public function get($key, $default = '')
    {
        $value = $this->redis->get($key);
        return !is_null($value) ? $value : $default;
    }

    public function remember($array, \Closure $closure)
    {
        $value = $this->get($array);
        if ($value) {
            return $value;
        }
        $value = $this->parseValue($closure());
        $this->set($array, $value);
        return $value;
    }

    public function has($key)
    {
        return $this->redis->exist($key);
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }


}