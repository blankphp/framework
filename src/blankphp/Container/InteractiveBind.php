<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Container;

class InteractiveBind
{
    private $keys;
    private $values;

    public function binds($key, $values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }
        $this->keys[$key] = $values;
        foreach ($values as $item) {
            $this->values[$item] = $key;
        }
    }

    public function verifyKey()
    {
    }

    public function verifyValue($key): bool
    {
        return isset($this->values[$key]);
    }

    public function get($key)
    {
        return $this->keys[$key] ?? $this->values[$key] ?? null;
    }

    public function getByKey($key, $default = [])
    {
        return $this->keys[$key] ?? $default;
    }

    public function getValue($key, $default = null)
    {
        return $this->values[$key] ?? $default;
    }
}
