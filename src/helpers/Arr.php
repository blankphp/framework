<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */
declare(strict_types=1);
namespace BlankQwq\Helpers;

class Arr
{
    /**
     * @param $first
     * @param $second
     * @param $func
     * @return array
     */
    public static function merge($first, $second, $func = true): array
    {
        return $func ? array_merge($first, $second) : $first + $second;
    }

    public static function get($name, $arr, $default = null)
    {
        $name = explode('.', $name);
        $value = null;
        foreach ($name as $item) {
            if (isset($arr[$item])) {
                $value = $value[$item];
            } else {
                return $default;
            }
        }
        return $value;
    }
}
