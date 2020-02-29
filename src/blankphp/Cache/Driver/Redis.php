<?php


namespace Blankphp\Cache\Driver;


use Blankphp\Cache\Contract\Driver;
use Blankphp\Facade\Application;
use Predis\Client;

class Redis implements Driver
{
    //连接存储
    protected $option;
    private $redis;
    protected static $instance;

    public function __construct(array $config)
    {
        $this->option = empty($config) ? $this->option : $config;
        //初始化连接
        $this->redis = new Client($this->option);
        //注册自己
        Application::instance('redis.connect', $this->redis);
        Application::instance('redis', $this);
    }


    public static function getInstance(array $config)
    {
        if (!empty(self::$instance)) {
            return self::$instance;
        } else {
            return self::$instance = new self($config);
        }
    }

    private function parseValue($value)
    {
        //把值转化为可存储的value
        if (is_string($value)) {
            return $value;
        } elseif (is_array($value)) {
            return json_encode($value);
        } else {
            return $value;
        }
    }

    public function set($key, $value, $ttl = null)
    {
        if (!is_null($ttl)) {
            return $this->redis->set($key, $this->parseValue($value), 'EX', $ttl);
        } else {
            return $this->redis->set($key, $this->parseValue($value));
        }
    }

    public function get($key, $default = '')
    {
        $value = $this->redis->get($key);
        return !is_null($value) ? $value : $default;
    }

    public function remember($array, \Closure $closure, $ttl = 0)
    {
        $value = $this->get($array);
        if ($value) {
            return $value;
        }
        $value = $this->parseValue($closure());
        $this->set($array, $value, $ttl);
        return $value;
    }

    public function has($key)
    {
        return $this->redis->exist($key);
    }

    public function flush()
    {
        // TODO: Implement flush() method.
        return $this->redis->flushdb();
    }

    public function forget($key)
    {
        // TODO: Implement flush() method.
    }


}