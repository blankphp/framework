<?php


namespace Blankphp\Console\Cache;


use Blankphp\Console\Console;
use Helpers\File;

class CacheConsole extends Console
{

    public function route()
    {
        //获取路由数组

        //缓存到指定位置

    }

    public function config()
    {
        $config = config();
        $text = '<?php return ' . var_export($config, true) . ';';
        $result = File::put(APP_PATH . '/cache/framework/config.php', $text);
        return $result ? " create config cache successful" . PHP_EOL : "create config cache error" . PHP_EOL;
    }

}