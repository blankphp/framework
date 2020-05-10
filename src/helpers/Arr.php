<?php

namespace BlankQwq\Helpers;

class Arr
{
    /**
     * @param $first
     * @param $second
     * @param bool $func
     * @return array
     */
    public static function merge($first, $second, $func = true): array
    {
        return $func?array_merge($first, $second): $first+ $second;
    }

}