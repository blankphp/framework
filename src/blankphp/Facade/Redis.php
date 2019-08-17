<?php


namespace Blankphp\Facade;


use Blankphp\Facade;

/**
 * Class Redis
 * @package Blankphp\Facade
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