<?php


namespace BlankPhp\Facade;


use BlankPhp\Facade;

class Cookie extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cookie';
    }

}