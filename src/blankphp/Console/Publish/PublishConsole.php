<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Console\Publish;

use BlankPhp\Console\Console;
use BlankQwq\Helpers\Str;

class PublishConsole extends Console
{
    public function vendor($name = '', $other = '')
    {
        //发布某个包
        if (empty($name)) {
            return '[name] is empty!!';
        }
        if (!class_exists($name)) {
            $name = Str::makeClassName($name, "\BlankPhp\Provider\\");
        }
        $provider = $this->app->make($name);
        $result = $provider->publish($other);

        return $result ? "Publish [{$name}] is ok" : "Publish [{$name}] error";
    }
}
