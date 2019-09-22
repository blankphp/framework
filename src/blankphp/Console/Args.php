<?php


namespace Blankphp\Console;

/**
 * Class Args
 * @package Blankphp\Console
 * Console的辅助函数
 */
class Args
{
    public function __invoke()
    {
        array_shift($argv);
        $mod = $argv[0];
        $command = explode(':', $mod);
        return array_filter($command);
    }
}