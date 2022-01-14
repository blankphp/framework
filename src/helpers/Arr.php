<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankQwq\Helpers;

class Arr
{
    /**
     * @param $first
     * @param $second
     * @param bool $func
     */
    public static function merge($first, $second, $func = true): array
    {
        return $func ? array_merge($first, $second) : $first + $second;
    }
}
