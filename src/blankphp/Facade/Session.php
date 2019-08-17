<?php


namespace Blankphp\Facade;


use Blankphp\Facade;

class Session extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'session';
    }
}