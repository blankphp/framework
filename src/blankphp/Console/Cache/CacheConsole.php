<?php


namespace BlankPhp\Console\Cache;


use BlankPhp\Console\Console;
use BlankQwq\Helpers\File;

class CacheConsole extends Console
{
    protected $option = [
        "route" => "route.php",
        "config" => "config.php"
    ];
    protected $dir = "";

    public function __construct($app)
    {
        parent::__construct($app);
        $this->dir = config('cache.framework');
        $this->option = array_merge($this->option, config('cache.file'));
    }

    public function route()
    {
        //获取路由数组
        $fileName = $this->option['route'];
        if (is_file($fileName)) {
            return $this->printLn("[route] is created ,if you want remove it ,please input cache:clear");
        }
        $route = $this->app->make('route')->cache();
        //缓存到指定位置
        $result = $this->cacheData($route, $fileName);
        return $result ? $this->printLn("Create [route] cache to [$fileName] successful") : $this->printLn("Create [route] cache to [$fileName] Error");
    }


    public function config()
    {
        $config = config();
        $fileName = $this->option['config'];
        if (is_file($fileName)) {
            return $this->printLn("[config] is created ,if you want remove it ,please input cache:clear");
        }
        $result = $this->cacheData($config, $fileName);
        return $result ? $this->printLn(" Create [config] cache to [$fileName] successful") : $this->printLn(" Create [config] cache to [$fileName] error");
    }

    public function clear($key = "")
    {
        if (!empty($key)) {
            $fileName[] = $this->option[$key];
        } else {
            $fileName = $this->option;
        }
        foreach ($fileName as $file) {
            File::delete($file);
        }
        return $this->printLn("Clear all cache in framework");
    }

    private function cacheData($data, $file)
    {
        $text = '<?php return ' . var_export($data, true) . ';';
        return File::put($file, $text);
    }
}