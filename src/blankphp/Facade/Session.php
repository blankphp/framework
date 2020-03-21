<?php


namespace BlankPhp\Facade;


use BlankPhp\Facade;

class Session extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'session';
    }
}