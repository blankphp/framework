<?php


namespace BlankPhp\Facade;


use BlankPhp\Facade;

class Log extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'log';
    }
}