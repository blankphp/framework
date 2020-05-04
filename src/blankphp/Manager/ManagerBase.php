<?php


namespace BlankPhp\Manager;

use BlankPhp\Application;

/**
 * Class ManagerBase
 * @package BlankPhp\Manager
 * 管理模式基类
 */
abstract class ManagerBase
{
    protected $drivers = [];

    protected $default = null;

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function driver($name = '', $config = [])
    {
        if (empty($name)) {
            return $this->drivers['default'] = $this->createDefaultDriver();
        }
        //开始创建对于handler
        $method = $this->formatCreateMethod($name);
        if (method_exists($this, $method)) {
            $driver = $this->{$method}(...$config);
        } else {
            $name = 'default';
            $driver = $this->createDefaultDriver();
        }
        return $this->drivers[$name] = $driver;
    }

    protected function formatCreateMethod($name): string
    {
        return sprintf('create%dDriver', ucfirst($name));
    }

    abstract public function createDefaultDriver();

    public function clear(): void
    {
        $this->drivers = [];
    }
}