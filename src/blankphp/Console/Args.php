<?php


namespace BlankPhp\Console;

/**
 * Class Args
 * @package BlankPhp\Console
 * Console的辅助函数
 */
class Args
{
    public static function capture($argv)
    {
        if (empty($argv)) {
            return [];
        }
        array_shift($argv);
        $mod = [];
        foreach ($argv as $item) {
            if (strstr($item, ":")) {
                $command = explode(':', $item);
                foreach ($command as $value)
                    $mod[] = $value;
            } else {
                $mod[] = $item;
            }
        }
        unset($argv);
        return array_map("trim", array_filter($mod));
    }
}