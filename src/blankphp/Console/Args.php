<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Console;

/**
 * Class Args.
 */
class Args
{
    public static function capture($argv): array
    {
        if (empty($argv)) {
            return [];
        }
        array_shift($argv);
        $mod = [];
        foreach ($argv as $item) {
            if (false !== strpos($item, ':')) {
                $command = explode(':', $item);
                foreach ($command as $value) {
                    $mod[] = $value;
                }
            } else {
                $mod[] = $item;
            }
        }
        unset($argv);

        return array_map('trim', array_filter($mod));
    }
}
