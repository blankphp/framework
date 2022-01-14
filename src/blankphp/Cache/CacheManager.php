<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Cache;

use BlankPhp\Base\Traits\FactoryClientTrait;
use BlankPhp\Manager\ManagerBase;

class CacheManager extends ManagerBase
{
    use FactoryClientTrait;

    private $tag = 'cache';

    public function createDefaultDriver()
    {
        return $this->createFromFactory(config('cache.driver'), $this->tag);
    }
}
