<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankQwq\Helpers;

class Str
{
    public static function makeClassName($name, $namespace = '')
    {
        if (class_exists($name)) {
            return $name;
        }

        return $namespace.ucfirst($name);
    }

    public static function merge($str1, $str2, $connect = '')
    {
        return $str1.$connect.$str2;
    }

    public static function random($length, $str = null)
    {
        $str = empty($str) ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz.+?' : $str;

        return substr(str_shuffle($str), random_int(0, strlen($str) - $length), $length);
    }

    public function studly(string $str): string
    {
        return $str;
    }
}
