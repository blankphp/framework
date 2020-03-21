<?php


namespace BlankPhp\Factory;


use BlankQwq\Helpers\Str;

class FactoryBase
{
    protected $name = 'default';
    //驱动设置
    protected $config = [];
    //驱动列表
    protected $project = [];
    //驱动实例
    protected $instance = [];

    //绑定此时driver

    public function __construct($config = [], $project = [])
    {
        $this->setDrivers($project);
        $this->setConfig($config);
    }

    /**
     * @param array $config
     */
    public function setConfig($config): void
    {
        $this->config = $config;
    }

    /**
     * @param $name
     * @param string $nickName
     * @param bool $register
     * @return mixed
     * 生产driver
     */
    public function factory($name, $nickName = 'default', $register = false)
    {
        //获取适配器
        $realName = $name;
        //解析名称
        [$name, $config] = $this->parseName($name);
        //如果app中有,那么直接返回app中的
        if (isset($this->instance[$realName]) && !empty($res = $this->instance[$realName])) {
            return $res;
        }
        $className = $this->getDrivers($name);
        //获取配置
        $classConfig = $this->getConfig($config);

        //创造driver,并存储再返回
        if ($register) {
            return $this->instance[$realName] = new $className($nickName, $classConfig);
        }

        return new $className($nickName, $classConfig);
    }

    //解析名称,并且返回key和config的索引
    public function parseName($name): array
    {
        $names = explode('.', $name);
        if (count($names) > 1) {
            $name = Str::merge('driver.', $names[0]);
            $config = $names;
        } else {
            $config = [$name, 'default'];
        }
        return array($name, $config);
    }

    /**
     * @param $key
     * @return array
     */
    public function getDrivers($key): array
    {
        return $this->project[$key];
    }

    /**
     * @param array $project
     */
    public function setDrivers($project): void
    {
        $this->project = $project;
    }

    /**
     * @param array|string $key
     * @param string $default
     * @return mixed
     */
    public function getConfig($key, $default = '')
    {
        if (is_array($key)) {
            $value = $this->config;
            foreach ($key as $k) {
                if (isset($value[$k])) {
                    $value = $value[$k];
                } else {
                    $value = [];
                    break;
                }
            }
            return $value;
        }
        return $this->config[$key] ?? $default;
    }

}