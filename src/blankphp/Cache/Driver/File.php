<?php


namespace Blankphp\Cache\Driver;


use Blankphp\Application;
use Blankphp\Cache\Contract\Driver;

class File implements Driver
{
    protected static $instance;
    public static $key;
    protected static $cacheTime = 0;
    public static $dir;
    protected $data;
    private $option;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public static function getInstance(Application $app = null)
    {
        if (!empty(self::$instance)) {
            return self::$instance;
        } else {
            return self::$instance = new self($app);
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
        $this->option = $option;
    }

    public function getFromFile($file)
    {
        //加载
        if (is_file(self::$dir . $file))
            $this->data = require(self::$dir . $file);
    }


    public function putToFile($data, $file)
    {
        $text = '<?php return ' . var_export($data, true) . ';';
        file_put_contents(self::$dir . $file, $text);
    }

    public function canRebuild($file, $descFile)
    {
        return filemtime($file) - filemtime(self::$dir . $descFile) < self::$cacheTime;
    }

    public function build()
    {


    }

    public function set($key, $value, $ttl = null)
    {
        $this->data[$key] = $value;
    }

    public function remember($array, \Closure $closure)
    {
        if ($this->has($array))
            return $this->data[$array];
        $this->data[$array] = $closure();
        return $this->data[$array];
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