<?php


namespace BlankPhp\Config;


use BlankPhp\Application;

class Config implements \ArrayAccess, \Iterator, \Countable
{
    public $config;
    protected $configPath = APP_PATH . 'config/';
    protected $current;


    public function setConfig($config): Config
    {
        $this->config = $config;
        return $this;
    }

    public function get($descNames, $default = '')
    {
        try {
            $config = $this->config;
            if (!is_array($descNames)) {
                $descNames = explode('.', $descNames);
                $descNames = array_filter($descNames);
            }
            foreach ($descNames as $descName) {
                $config = $config[$descName];
            }
            return $config;
        } catch (\Exception $exception) {
            return $default;
        }
    }

    public function set($key, $value): void
    {

    }

    public function all()
    {
        return $this->config;
    }


    public function count()
    {
        return count($this->config);
    }

    public function current()
    {

    }

    public function next()
    {

    }

    public function key()
    {

    }


    public function valid()
    {

    }

    public function rewind()
    {

    }

    public function offsetExists($offset): bool
    {
        return isset($this->config[$offset]);
    }


    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }

}