<?php


namespace BlankPhp\Facade;


use BlankPhp\Facade;

/**
 * Class Redis
 * @package BlankPhp\Facade
 * @method set
 * @method get
 * @method has
 */
class Redis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'redis';
    }
}