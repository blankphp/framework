<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Driver\Contract;

interface Driver
{
    public function __construct($name = 'default', $option = []);

    public function parseValue($value);

    public function valueParse($value, $option = []);

    public function set($key, $value, $ttl = null);

    public function delete($key);

    public function get($key, $default = '');

    public function remember($array, \Closure $closure, $ttl = 0);

    public function has($key);

    public function flush();

    public function forget($key);
}
