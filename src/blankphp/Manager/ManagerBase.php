<?php


namespace BlankPhp\Manager;

use BlankPhp\Application;
use BlankPhp\Exception\Method\NotFoundMethodException;

/**
 * Class ManagerBase
 * @package BlankPhp\Manager
 * 管理模式基类
 */
abstract class ManagerBase
{
    protected $drivers = [];

    protected $initCreateDefaultDriver = false;

    protected const FORMAT_METHOD = 'create%sDriver';

    protected $app;

    /**
     * ManagerBase constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        if ($this->initCreateDefaultDriver) {
            $this->default = $this->createDefaultDriver();
        }
    }

    /**
     * @param string $name
     * @return mixed|void|null
     */
    public function driver(string $name = null)
    {
        $name = $name ?? $this->getDefaultName();

        if (isset($this->drivers[$name])) {
            return $this->drivers[$name];
        }
        $method = $this->formatCreateMethod($name);
        if (method_exists($this, $method)) {
            $driver = $this->{$method}();
            return $this->drivers[$name] = $driver;
        }

        return $this->drivers[$name] = $this->app->make($name);
    }

    /**
     * @param string $name
     */
    public function forgetDriver($name = ''): void
    {
        unset($this->drivers[$name]);
    }

    /**
     * @param $name
     * @return string
     */
    protected function formatCreateMethod($name): string
    {
        return sprintf(self::FORMAT_METHOD, ucfirst($name));
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->drivers = [];
        $this->default = null;
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        if (empty($this->default)) {
            $this->createDefaultDriver();
        }
        return $this->driver()->{$name}(...$arguments);
    }

    /**
     * @return string
     */
    protected function getDefaultName(): string
    {
        return 'default';
    }

    /**
     * @return mixed
     */
    abstract public function createDefaultDriver();
}