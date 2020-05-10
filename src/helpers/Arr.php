<?php

namespace BlankQwq\Helpers;

class Arr
{
    /**
     * @param $first
     * @param $second
     * @return array
     */
    public static function merge($first, $second): array
    {
        return array_merge($first, $second);
    }

}