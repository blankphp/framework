<?php


namespace BlankPhp\Facade;


use BlankPhp\Facade;

class Database extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'db';
    }

}