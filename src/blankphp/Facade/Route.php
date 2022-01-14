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

class Route extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'route';
    }
}
