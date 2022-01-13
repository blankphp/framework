<?php


namespace BlankPhp\Facade;


use BlankPhp\Facade;

/**
 * Class Driver
 * @package BlankPhp\Facade
 * @method setConfig($config)
 * @method factory($name, $nickName = 'default', $register = false)
 * @method parseName($name)
 * @method getDrivers($key)
 * @method setDrivers($project)
 * @method getConfig($key, $default = '')
 */
class Driver extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor():string
    {
        return 'driver.factory';
    }

}