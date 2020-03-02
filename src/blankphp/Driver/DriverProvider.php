<?php


namespace Blankphp\Driver;


use Blankphp\Provider\Provider;

class DriverProvider extends Provider
{
    protected $registers = [
        "driver.file" => FileDriver::class,
        "driver.mysql" => MysqlDriver::class,
        "driver.redis" => RedisDriver::class,
        "driver.database" => DataBaseDriver::class,
        'driver.memcache' => MemeCacheDriver::class,
        'driver.manager' => DriverManager::class,
    ];

    public function boot()
    {
        $config = $this->app->make('config')->get('driver');
        $driver = new DriverManager($config, $this->registers);
        $this->app->instance("driver.manager", $driver);
    }


    public function register()
    {

        foreach ($this->registers as $k => $item) {
            $this->app->bind($item, $k);
        }
    }

}