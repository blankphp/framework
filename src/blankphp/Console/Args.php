<?php


namespace BlankPhp\Console;

/**
 * Class Args
 * @package BlankPhp\Console
 * Console的辅助函数
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
            if (strpos($item, ':') !== false) {
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