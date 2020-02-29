<?php


namespace Blankphp\Cache\Driver;


use Blankphp\Application;
use Blankphp\Cache\Contract\Driver;

class File implements Driver
{
    protected static $instance;
    public static $key;
    protected static $cacheTime = 0;
    public static $dir = APP_PATH . "/cache/kv";
    protected $data;
    private $option = [
        'tag' => '_key_'
    ];

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public static function getInstance(array $config = [])
    {
        if (!empty(self::$instance)) {
            return self::$instance;
        } else {
            return self::$instance = new self($config);
        }
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function __construct($option)
    {
        $this->option = array_merge($this->option, $option);
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
        \Helpers\File::put($this->build($key), $value);
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
}