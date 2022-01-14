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
 * Class Driver.
 *
 * @method setConfig($config)
 * @method factory($name, $nickName = 'default', $register = false)
 * @method parseName($name)
 * @method getDrivers($key)
 * @method setDrivers($project)
 * @method getConfig($key, $default = '')
 */
class Driver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'driver.factory';
    }
}
