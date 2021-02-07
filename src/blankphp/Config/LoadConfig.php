<?php


namespace BlankPhp\Config;


use BlankPhp\Application;

class LoadConfig
{
    private $configPath = APP_PATH . 'config/';
    private $handler;

    /**
     * @param Application $app
     */
    public function load(Application $app): void
    {
        if (!is_file(APP_PATH . 'cache/framework/config.php')) {
            $config = $this->loadConfigFile();
        } else {
            $config = require APP_PATH . 'cache/framework/config.php';
        }
        $c = new Config();
        $c->setConfig($config);
        $app->instance('config', $c);
        date_default_timezone_set($c->get(['app', 'timezone'], 'Asia/Shanghai'));
        mb_internal_encoding('UTF-8');
    }

    /**
     * @return array
     */
    public function loadConfigFile(): array
    {
        $config = [];
        foreach (glob($this->configPath . '*.php') as $file) {
            $fileName = substr($file, strlen($this->configPath), strlen($file));
            $position = strpos($fileName, '.');
            $fileName = substr($fileName, 0, $position);
            $config[$fileName] = require $file;
        }
        return $config;
    }

}