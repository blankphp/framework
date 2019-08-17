<?php


namespace Blankphp\Facade;


use Blankphp\Facade;

class Database extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'db';
    }

}