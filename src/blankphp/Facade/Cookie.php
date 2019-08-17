<?php


namespace Blankphp\Facade;


use Blankphp\Facade;

class Cookie extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cookie';
    }

}