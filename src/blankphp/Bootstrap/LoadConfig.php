<?php


namespace BlankPhp\Bootstrap;


use BlankPhp\Application;
use BlankPhp\Config\Config;
use const APP_PATH;

class LoadConfig extends AbstractBootstrap
{
    private $configPath = APP_PATH . 'config/';


    public function boot(Application $app)
    {
        /** @var Config $c */
        $c = $app->make('config');
        $c->setConfig($this->load());
        date_default_timezone_set($c->get(['app', 'timezone'], 'Asia/Shanghai'));
        mb_internal_encoding($c->get(['app', 'encode'], 'UTF-8'));
    }
    public function load()
    {
        if (!is_file(APP_PATH . 'cache/framework/config.php')) {
            $config = $this->loadConfigFile();
        } else {
            $config = require APP_PATH . 'cache/framework/config.php';
        }
        return $config?:[];
    }

    private function loadConfigFile(): array
    {
        $config = [];
        foreach (glob($this->configPath . "*.php") as $file) {
            $fileName = substr($file, strlen($this->configPath), strlen($file));
            $position = strpos($fileName, '.');
            //是否截取其中的代码
            $fileName = substr($fileName, 0, $position);
            $config[$fileName] = require($file);
        }
        return $config;
    }


}