<?php


namespace BlankPhp\Facade;


use BlankPhp\Facade;

class Cache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cache';
    }
}