<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Route;

use BlankPhp\PipeLine\PipeLine;

class Pipe extends PipeLine
{
    public function run(\Closure $closure)
    {
        return call_user_func(array_reduce(array_reverse($this->middleware), $this->getAlice(), $closure));
    }
}
