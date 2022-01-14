<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Driver;

class MemeCacheDriver extends Driver
{
    public function __construct($name = 'default', $option = [])
    {
    }

    public function set($key, $value, $ttl = null)
    {
        // TODO: Implement set() method.
    }

    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    public function get($key, $default = '')
    {
        // TODO: Implement get() method.
    }

    public function remember($array, \Closure $closure, $ttl = 0)
    {
        // TODO: Implement remember() method.
    }

    public function has($key)
    {
        // TODO: Implement has() method.
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }

    public function forget($key)
    {
        // TODO: Implement forget() method.
    }
}
