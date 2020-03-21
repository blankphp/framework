<?php


namespace BlankPhp\Driver;


class FileDriver extends Driver
{


    public static $key;
    protected static $cacheTime = 0;
    protected $config = [];

    public function __construct($name = "default", $option = [])
    {
        $this->config = array_merge($this->config, $option);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


    public function getFromFile($file)
    {
        //加载
        $data = [];
        if (is_file(self::$dir . $file))
            $data = require(self::$dir . $file);
        $this->data = array_merge($this->data, $data);
    }


    public function canRebuild($file, $descFile)
    {
        return filemtime($file) - filemtime(self::$dir . $descFile) < self::$cacheTime;
    }

    public function build($key)
    {
        return $this->option['tag'] . $key;
    }

    public function set($key, $value, $ttl = null)
    {
        $value = ["value" => $value, "ttl" => time() + $ttl];
        \BlankQwq\Helpers\File::put($this->build($key), $value);
    }

    public function remember($key, \Closure $closure, $ttl = null)
    {
        $this->build($key);
        if ($this->has($key))
            return $this->get($key);
        $this->set($key, $closure(), $ttl);
        return $this->data[$key];
    }

    public function has($key)
    {
        if (isset($this->data[$key]))
            return true;
        else
            return false;
    }

    public function get($key, $default = '')
    {
        if ($this->has($key))
            return $this->data[$key];
        return $default;
    }


    public function flush()
    {
        // TODO: Implement flush() method.
    }

    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    public function forget($key)
    {
        // TODO: Implement forget() method.
    }
}