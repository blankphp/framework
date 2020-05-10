<?php


namespace BlankPhp\Cache;


use BlankPhp\Application;
use BlankPhp\Base\Traits\FactoryClientTrait;
use BlankPhp\Contract\Container;
use BlankPhp\Facade\Driver;
use BlankPhp\Manager\ManagerBase;
use BlankQwq\Helpers\Str;

class CacheManager extends ManagerBase
{
    use FactoryClientTrait;

    private $tag = 'cache';

    public function createDefaultDriver()
    {
        return $this->createFromFactory(config('cache.driver'), $this->tag);
    }
}