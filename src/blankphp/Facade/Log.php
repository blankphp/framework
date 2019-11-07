<?php


namespace Blankphp\Facade;


use Blankphp\Facade;

class Log extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'log';
    }
}