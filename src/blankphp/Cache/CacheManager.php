<?php


namespace BlankPhp\Cache;


use BlankPhp\Application;
use BlankPhp\Contract\Container;
use BlankPhp\Facade\Driver;
use BlankPhp\Manager\ManagerBase;
use BlankQwq\Helpers\Str;

class CacheManager extends ManagerBase
{
    private $config;
    private $tag = 'cache';

    public function __construct()
    {
        parent::__construct();
        $handler = Driver::factory(config('cache'), $this->tag);
    }

}