<?php


namespace BlankPhp\Manager;

/**
 * Class ManagerBase
 * @package BlankPhp\Manager
 * 管理模式基类
 */
class ManagerBase
{
    protected $drivers = [];

    protected $default = null;

    public function __construct()
    {

    }

    public function createDefaultDriver()
    {

    }

    public function driver()
    {

    }


    public function clear(): void
    {
        $this->drivers = [];
    }
}