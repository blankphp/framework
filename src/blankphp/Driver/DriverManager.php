<?php


namespace Blankphp\Driver;


use Helpers\Str;

class DriverManager
{

    protected $name = "default";
    //驱动设置
    protected $config = [];
    //驱动列表
    protected $drivers = [];
    //驱动实例
    protected $instance = [];

    public function __construct($config = [], $drivers = [])
    {
        $this->setDrivers($drivers);
        $this->setConfig($config);
    }

    public function factory($name, $nickName = "default", $register = false)
    {
        //获取适配器
        list($name, $config) = $this->parseName($name);
        $storeName = Str::merge($nickName, $name, '.');
        if (isset($this->instance[$storeName]) && !empty($res = $this->instance[$storeName])) {
            return $res;
        }
        $className = $this->getDrivers($name);
        //获取配置
        $classConfig = $this->getConfig($config);
        //创造driver,并存储再返回
        if ($register) {
            return $this->instance[$storeName] = new $className($nickName, $classConfig);
        } else {
            return new $className($nickName, $classConfig);
        }
    }

    //解析名称,并且返回key和config的索引
    public function parseName($name)
    {
        $names = explode('.', $name);
        if (count($names) > 1) {
            $name = Str::merge("driver.", $names[0]);
            $config = $names;
        } else {
            $config = [$name, "default"];
        }
        return array($name, $config);
    }

    /**
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public function getConfig($key, $default = "")
    {
        if (is_array($key)) {
            $value = $this->config;
            foreach ($key as $key) {
                if (isset($value[$key])) {
                    $value = $value[$key];
                } else {
                    $value = [];
                    break;
                }
            }
            return $value;
        }
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getDrivers($key)
    {
        return $this->drivers[$key];
    }

    /**
     * @param array $drivers
     */
    public function setDrivers($drivers)
    {
        $this->drivers = $drivers;
    }


}