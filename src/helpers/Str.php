<?php

namespace BlankQwq\Helpers;

class Str
{
    public static function makeClassName($name, $namespace = '')
    {
        if (class_exists($name)) {
            return $name;
        }
        return $namespace . ucfirst($name);
    }

    public static function merge($str1, $str2, $connect = '')
    {
        return $str1 . $connect . $str2;
    }

    public static function random($length, $str = null)
    {
        $str = empty($str) ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz.+?' : $str;

        return substr(str_shuffle($str), random_int(0, strlen($str) - $length), $length);

    }
}