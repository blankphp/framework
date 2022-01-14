<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Facade;

use BlankPhp\Facade;

/**
 * Class Redis.
 *
 * @method set
 * @method get
 * @method has
 */
class Redis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'redis';
    }
}
