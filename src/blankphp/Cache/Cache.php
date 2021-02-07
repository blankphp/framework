<?php


namespace BlankPhp\Cache;

use BlankPhp\Application;
use \BlankPhp\Driver\Contract\Driver;

class Cache
{
    /**
     * @var Driver
     */
    private $handler;
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var int
     */
    private $writeCount = 0;
    /**
     * @var int
     */
    private $getCount = 0;
    /**
     * @var string
     */
    protected $tag;
    /**
     * @var string[]
     */
    protected $config = [
        'prefix' => '',
    ];

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function setOption($config): void
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @param array $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getWriteCount(): int
    {
        return $this->writeCount;
    }

    /**
     * @param int $writeCount
     */
    public function setWriteCount($writeCount): void
    {
        $this->writeCount = $writeCount;
    }

    /**
     * @return int
     */
    public function getGetCount(): int
    {
        return $this->getCount;
    }

    /**
     * @param int $getCount
     */
    public function setGetCount($getCount): void
    {
        $this->getCount = $getCount;
    }


    /**
     * @return Driver
     */
    public function getHandler(): Driver
    {
        return $this->handler;
    }

    /**
     * @param Driver $handler
     */
    public function setHandler(Driver $handler): void
    {
        $this->handler = $handler;
    }

    /***
     * @param $key
     * @param $value
     * @param null $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->getHandler()->set($key, $value, $ttl);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->getHandler()->get($key, $default);
    }

    /**
     * @param $key
     * @param \Closure $closure
     * @return mixed
     */
    public function remember(string $key, \Closure $closure)
    {
        return $this->getHandler()->remember($key, $closure());
    }

    /**
     * @param $key
     * @return mixed
     */
    public function has($key)
    {
        return $this->getHandler()->has($key);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->getHandler()->$name(...$arguments);
    }

}