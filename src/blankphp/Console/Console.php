<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Console;

class Console
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function printLn($message)
    {
        echo $message.PHP_EOL;

        return true;
    }
}
