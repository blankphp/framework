<?php


namespace BlankPhp\Driver;


use BlankPhp\Provider\Provider;

class DriverProvider extends Provider
{
    protected $registers = [
        'driver.file' => FileDriver::class,
        'driver.redis' => RedisDriver::class,
        'driver.database' => DataBaseDriver::class,
        'driver.memcache' => MemeCacheDriver::class,
        'driver.factory' => DriverFactory::class,
    ];


    public function boot()
    {
        $config = $this->app->make('config')->get('driver');
        $driver = new DriverFactory($config, $this->registers);
        $this->app->instance('driver.factory', $driver);
    }


    public function register()
    {
        foreach ($this->registers as $k => $item) {
            $this->app->bind($item, $k);
        }
    }

}