<?php


namespace BlankPhp\Facade;


use BlankPhp\Facade;

class DB extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'db';
    }

}