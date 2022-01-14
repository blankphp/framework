<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Base\Traits;

use BlankPhp\Driver\DriverFactory;
use BlankPhp\Facade\Driver;

trait FactoryClientTrait
{
    /**
     * @var DriverFactory
     */
    private $factory;

    private function createFromFactory($name, $nickName = 'default', $register = false)
    {
        return $this->getFactory()->factory($name, $nickName, $register);

    }

    private function getFactory()
    {
        if (empty($this->factory)) {
            $this->factory = Driver::getFromApp();
        }

        return $this->factory;
    }
}
