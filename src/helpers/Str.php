<?php

namespace Helpers;

class Str
{
    static public function makeClassName($name, $namespace = '')
    {
        if (class_exists($name)) {
            return $name;
        }
        return $namespace . ucfirst($name);
    }

    static public function merge($str1, $str2,$connect="")
    {
        return $str1 .$connect. $str2;
    }

    static public function random($length)
    {
        $strs = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz.+?";
        $random = substr(str_shuffle($strs), mt_rand(0, strlen($strs) - $length), $length);
        return $random;
    }
}