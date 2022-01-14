<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Proxy;

use BlankPhp\Proxy\Traits\GetAllMethod;

class Proxy
{
    use GetAllMethod;

    public $name;
    protected $origin;
    protected $methods = [];

    public function __call($name, $arguments)
    {
    }
}
