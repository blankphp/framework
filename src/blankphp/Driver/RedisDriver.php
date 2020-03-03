<?php


namespace Blankphp\Driver;


use Blankphp\Exception\Redis\RedisConnectException;
use Blankphp\Facade\Application;
use Predis\Client;
use Predis\Connection\ConnectionException;


class RedisDriver extends Driver
{
    //连接存储
    protected $option;
    private $redis;
    protected static $instance;

    public function __construct($name = "default", $option = [])
    {
        $this->option = empty($option) ? $this->option : $option;
        $app = Application::getInstance();
        try {
            //初始化连接
            $this->redis = $app->make(Client::class, [$this->option]);
            $app->instance('redis.connect.' . $name, $this->redis);
        } catch (ConnectionException $exception) {
            throw new RedisConnectException($exception->getMessage());
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

    public function delete($key)
    {
        return $this->redis->del($key);
    }


    public function get($key, $default = '')
    {
        $value = $this->redis->get($key);
        return !is_null($value) ? $this->valueParse($value) : $default;
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
        $value = $this->get($key);
        $this->delete($key);
        return $value;
    }




}