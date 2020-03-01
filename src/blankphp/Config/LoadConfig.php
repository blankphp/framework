<?php


namespace Blankphp\Config;


use Blankphp\Application;

class LoadConfig
{
    private $configPath = APP_PATH . 'config/';

    public function load(Application $app)
    {
        if (!is_file(APP_PATH . 'cache/framework/config.php')) {
            $config = $this->loadConfigFile();
        } else {
            $config = require APP_PATH . 'cache/framework/config.php';
        }
        $c = new Config();
        $c->setConfig($config);
        $app->instance('config', $c);
    }

    public function loadConfigFile()
    {
        //暂时耦合 -- 等会使用load解开耦合
        $config = [];
        foreach (glob($this->configPath . "*.php") as $file) {
            $fileName = substr($file, strlen($this->configPath), strlen($file));
            $position = strpos($fileName, '.');
            //是否截取其中的代码
            $fileName = substr($fileName, 0, $position);
            $config[$fileName] = require $file;
        }
        return $config;
    }

}