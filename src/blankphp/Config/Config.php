<?php


namespace Blankphp\Config;


use Blankphp\Application;

class Config implements \ArrayAccess, \Iterator, \Countable
{
    public $config;
    protected $configPath = APP_PATH . 'config/';
    protected $current;


    public function setConfig($config)
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
            unset($descNames, $default);
            return $config;
        } catch (\Exception $exception) {
            return $default;
        }
    }

    public function set($key, $value)
    {
        //获取driver

        //利用driver保存并刷新对应文件

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

    public function offsetExists($offset)
    {

    }


    public function offsetGet($offset)
    {

    }

    public function offsetSet($offset, $value)
    {

    }

    public function offsetUnset($offset)
    {

    }

}