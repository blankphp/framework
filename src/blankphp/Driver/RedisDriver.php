<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Driver;

use BlankPhp\Connect\Connect;
use BlankPhp\Exception\Redis\RedisConnectException;
use BlankPhp\Facade\Application;
use Predis\Client;
use Predis\Connection\ConnectionException;

class RedisDriver extends Driver implements Connect
{
    /**
     * @var array
     */
    protected $option;
    /**
     * @var Client
     */
    private $redis;

    public function __construct($name = 'default', $option = [])
    {
        $this->option = empty($option) ? $this->option : $option;
        try {
            //初始化连接
            $this->connect($name);
        } catch (ConnectionException $exception) {
            throw new RedisConnectException($exception->getMessage());
        }
    }

    public function set($key, $value, $ttl = null)
    {
        if (null !== $ttl) {
            return $this->redis->set($key, $this->parseValue($value), 'EX', $ttl);
        }

        return $this->redis->set($key, $this->parseValue($value));
    }

    public function delete($key): int
    {
        return $this->redis->del($key);
    }

    public function get($key, $default = '')
    {
        $value = $this->redis->get($key);

        return null !== $value ? $this->valueParse($value) : $default;
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

    public function has($key): int
    {
        return $this->redis->exists($key);
    }

    public function flush()
    {
        return $this->redis->flushdb();
    }

    public function forget($key)
    {
        $value = $this->get($key);
        $this->delete($key);

        return $value;
    }

    public function disconnect(): void
    {
        $this->redis->disconnect();
    }

    public function connect($name = 'default'): Client
    {
        /** @var \BlankPhp\Application $app */
        $app = Application::getInstance();
        $this->redis = $app->make(Client::class, [$this->option]);
        $app->instance('redis.connect.'.$name, $this->redis);
        $app->pushConnect($this->redis);

        return $this->redis;
    }

    public function reconnect(): void
    {
        if (!empty($this->redis)) {
            $this->redis->connect();
        }
        $this->connect();
    }
}
