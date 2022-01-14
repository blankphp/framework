<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Contract;

use BlankPhp\Application;

interface Kernel
{
    public function __construct(Application $app);

    public function handle($request);
}
